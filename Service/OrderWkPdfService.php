<?php
namespace Plugin\OrderPdf\Service;

use Eccube\Application;
use Plugin\OrderPdf\Entity\OrderPdfConfig;
use Eccube\Repository\OrderRepository;
use Eccube\Entity\BaseInfo;

class OrderWkPdfService
{
    /**
     * @var string
     */
    const DEFAULT_TEMPLATE = 'OrderPdf/Resource/template/admin/output_pdf.twig';

    /**
     * @var integer
     */
    const DEFAULT_TIMEOUT = 15;

    /**
     * @var string
     */
    protected $pdfFilePath = '';

    /**
     * @var string
     */
    protected $pdfFileName = '';

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var OrderPdfConfig
     */
    protected $OrderPdfConfig;

    /**
     * @var OrderRepository
     */
    protected $OrderRepository;

    /**
     * @var BaseInfo
     */
    protected $BaseInfo;

    /**
     * OrderWkPdfService constructor.
     *
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->OrderPdfConfig = $app['orderpdf.repository.order_pdf_config']->find(1);
        $this->OrderRepository = $app['eccube.repository.order'];
        $this->BaseInfo = $app['eccube.repository.base_info']->get();
    }

    /**
     * Check wkhtmlpdf enable
     *
     * @return boolean
     */
    public function isEnable()
    {
        return $this->OrderPdfConfig && $this->OrderPdfConfig->getWkhtmlExecPath();
    }

    /**
     * Make pdf file
     *
     * @return boolean
     */
    public function makePdf(array $formData)
    {
        $ids = explode(',', $formData['ids']);
        $html = '';
        foreach ($ids as $id) {
            $Order = $this->OrderRepository->find($id);
            $response = $this->app->render(static::DEFAULT_TEMPLATE, array(
                'Order' => $Order,
                'BaseInfo' => $this->BaseInfo
            ));

            $html .= $response->getContent();
        }

        $ids = implode('_', $ids);
        $htmlFileName = 'nouhinsyo-No'.$ids.'.'.time().'.html';
        $htmlFilePath = $this->getHtmlTempPath() . DIRECTORY_SEPARATOR . $htmlFileName;
        $pdfFileName =  'nouhinsyo-No'.$ids.'.pdf';
        $pdfFilePath =  $this->getPdfTempPath() . DIRECTORY_SEPARATOR .$pdfFileName;
        file_put_contents($htmlFilePath, $response->getContent());
        $wkHtmlBinPath = $this->OrderPdfConfig->getWkhtmlExecPath();

        passthru("{$wkHtmlBinPath} {$htmlFilePath} {$pdfFilePath}");
        $timeOut = $this->OrderPdfConfig->getTimeout() ?: static::DEFAULT_TIMEOUT;
        $success = false;
        while ($timeOut--) {
            $success = file_exists($pdfFilePath);
            if ($success) {
                break;
            }
            sleep(1);
        }

        $this->pdfFileName = $pdfFileName;
        $this->pdfFilePath = $pdfFilePath;

        return $success;
    }

    /**
     * Output pdf file
     *
     * @return mixed
     */
    public function outputPdf()
    {
        return  file_exists($this->pdfFilePath) ? file_get_contents($this->pdfFilePath) : '';
    }

    /**
     * Get PdfFileName
     *
     * @return string
     */
    public function getPdfFileName()
    {
        return $this->pdfFileName;
    }

    /**
     * Get html temporary path
     *
     * @return string
     */
    public function getHtmlTempPath()
    {
        return sys_get_temp_dir();
    }

    /**
     * Get pdf temporary path
     *
     * @return string
     */
    public function getPdfTempPath()
    {
        return sys_get_temp_dir();
    }
}