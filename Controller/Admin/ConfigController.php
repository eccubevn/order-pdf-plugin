<?php
namespace Plugin\OrderPdf\Controller\Admin;

use Doctrine\ORM\EntityManager;
use Eccube\Application;
use Eccube\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConfigController extends AbstractController
{
    /**
     * @param Application $app
     * @param Request $request
     *
     * @return Response
     */
    public function index(Application $app, Request $request)
    {
        $config = $app['orderpdf.repository.order_pdf_config']->find(1);
        if (!$config) {
            throw new NotFoundHttpException();
        }

        $form = $app['form.factory']->createBuilder('admin_order_pdf_config', $config)->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $app['orm.em'];
                $em->persist($config);
                $em->flush();

                log_info('Order Pdf Config', array('status' => 'success'));

                $app->addSuccess('plugin.admin.order_pdf_config.save.complete', 'admin');
            } catch (\Exception $e) {
                log_error('Product review config', array('status' => $e->getMessage()));

                $app->addError('plugin.admin.order_pdf_config.save.error', 'admin');
            }
        }

        return $app->render('OrderPdf/Resource/template/admin/config.twig', array('form' => $form->createView()));
    }
}