<?php
/*
 * This file is part of the Order Pdf plugin
 *
 * Copyright (C) 2016 LOCKON CO.,LTD. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\OrderPdf\Controller;

use Eccube\Controller\AbstractController;
use Eccube\Util\EntityUtil;
use Plugin\OrderPdf\Entity\OrderPdf;
use Plugin\OrderPdf\Form\Type\OrderPdfType;
use Plugin\OrderPdf\Repository\OrderPdfRepository;
use Plugin\OrderPdf\Service\OrderPdfService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class OrderPdfController.
 */
class OrderPdfController extends AbstractController
{
    /** @var  OrderPdfRepository */
    protected $orderPdfRepository;

    /** @var  OrderPdfService */
    protected $orderPdfService;

    /**
     * OrderPdfController constructor.
     * @param OrderPdfRepository $orderPdfRepository
     * @param OrderPdfService $orderPdfService
     */
    public function __construct(OrderPdfRepository $orderPdfRepository, OrderPdfService $orderPdfService)
    {
        $this->orderPdfRepository = $orderPdfRepository;
        $this->orderPdfService = $orderPdfService;
    }


    /**
     * 納品書の設定画面表示.
     *
     * @Route("%eccube_admin_route%/plugin/order-pdf", name="plugin_admin_order_pdf");
     *
     * @param Request     $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws NotFoundHttpException
     */
    public function index(Request $request)
    {
        // requestから受注番号IDの一覧を取得する.
        $ids = $request->get('ids', []);

        if (count($ids) == 0) {
            $this->addError('admin.plugin.order_pdf.parameter.notfound', 'admin');
            log_info('The Order cannot found!');

            return $this->redirect($this->generateUrl('admin_order'));
        }

        $OrderPdf = $this->orderPdfRepository->find($this->getUser());

        if (EntityUtil::isEmpty($OrderPdf)) {
            $OrderPdf = new OrderPdf();
            $OrderPdf
                ->setTitle(trans('admin.plugin.order_pdf.title.default'))
                ->setMessage1(trans('admin.plugin.order_pdf.message1.default'))
                ->setMessage2(trans('admin.plugin.order_pdf.message2.default'))
                ->setMessage3(trans('admin.plugin.order_pdf.message3.default'));
        }

        /**
         * @var FormBuilder $builder
         */
        $builder = $this->formFactory->createBuilder(OrderPdfType::class, $OrderPdf);

        /* @var Form $form */
        $form = $builder->getForm();

        // Formへの設定
        $form->get('ids')->setData(implode(',', $ids));

        return $this->render('OrderPdf/Resource/template/admin/order_pdf.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * 作成ボタンクリック時の処理
     * 帳票のPDFをダウンロードする.
     *
     * @Route("%eccube_admin_route%/plugin/order-pdf/download", name="plugin_admin_order_pdf_download");
     *
     * @param Request     $request
     *
     * @return Response
     *
     * @throws BadRequestHttpException
     */
    public function download(Request $request)
    {
        /**
         * @var FormBuilder $builder
         */
        $builder = $this->formFactory->createBuilder(OrderPdfType::class);

        /* @var Form $form */
        $form = $builder->getForm();
        $form->handleRequest($request);

        // Validation
        if (!$form->isValid()) {
            log_info('The parameter is invalid!');

            return $this->render('OrderPdf/Resource/template/admin/order_pdf.twig', array(
                'form' => $form->createView(),
            ));
        }

        $arrData = $form->getData();

        // 購入情報からPDFを作成する
        $status = $this->orderPdfService->makePdf($arrData);

        // 異常終了した場合の処理
        if (!$status) {
            $this->addError('admin.plugin.order_pdf.download.failure', 'admin');
            log_info('Unable to create pdf files! Process have problems!');

            return $this->render('OrderPdf/Resource/template/admin/order_pdf.twig', array(
                'form' => $form->createView(),
            ));
        }

        // ダウンロードする
        $response = new Response(
            $this->orderPdfService->outputPdf(),
            200,
            array('content-type' => 'application/pdf')
        );

        // レスポンスヘッダーにContent-Dispositionをセットし、ファイル名をreceipt.pdfに指定
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$this->orderPdfService->getPdfFileName().'"');
        log_info('OrderPdf download success!', array('Order ID' => implode(',', $request->get('ids', []))));

        $isDefault = isset($arrData['default']) ? $arrData['default'] : false;
        if ($isDefault) {
            // Save input to DB
            $arrData['admin'] = $this->getUser();

            $this->orderPdfRepository->save($arrData);
        }

        return $response;
    }

    /**
     * requestから注文番号のID一覧を取得する.
     *
     * @param Request $request
     *
     * @return array $isList
     */
    protected function getIds(Request $request)
    {
        $isList = [];
        // その他メニューのバージョン
        $query = $request->get('ids', []);
        if ($query) {
            sort($isList);
        }

        return $isList;
    }
}
