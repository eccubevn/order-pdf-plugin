<?php
namespace Plugin\OrderPdf\Entity;

use Eccube\Entity\AbstractEntity;

class OrderPdfConfig extends AbstractEntity
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $wkhtml_exec_path;

    /**
     * @var integer
     */
    protected $timeout;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get wkhtml_exec_path
     *
     * @return string
     */
    public function getWkhtmlExecPath()
    {
        return $this->wkhtml_exec_path;
    }

    /**
     * Set wkhtml_exec_path
     *
     * @param string $wkhtmlExecPath
     *
     * @return $this
     */
    public function setWkhtmlExecPath($wkhtmlExecPath)
    {
        $this->wkhtml_exec_path = $wkhtmlExecPath;
        return $this;
    }

    /**
     * Get timeout
     *
     * @return integer
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set timeout
     *
     * @param integer $timeout
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }
}