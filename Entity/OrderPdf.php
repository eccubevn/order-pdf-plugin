<?php
/*
 * This file is part of the OrderPdf plugin
 *
 * Copyright (C) 2016 LOCKON CO.,LTD. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\OrderPdf\Entity;

use Eccube\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * OrderPdf
 *
 * @ORM\Table(name="plg_order_pdf")
 * @ORM\Entity(repositoryClass="Plugin\OrderPdf\Repository\OrderPdfRepository")
 */
class OrderPdf extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="ids", type="string")
     */
    private $ids;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="issue_date", type="datetimetz")
     */
    private $issue_date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="message1", type="string")
     */
    private $message1;

    /**
     * @var string
     *
     * @ORM\Column(name="message2", type="string")
     */
    private $message2;

    /**
     * @var string
     *
     * @ORM\Column(name="message3", type="string")
     */
    private $message3;

    /**
     * @var string
     *
     * @ORM\Column(name="note1", type="string")
     */
    private $note1;

    /**
     * @var string
     *
     * @ORM\Column(name="note2", type="string")
     */
    private $note2;

    /**
     * @var string
     *
     * @ORM\Column(name="note3", type="string")
     */
    private $note3;

    /**
     * @var integer
     *
     * @ORM\Column(name="default", type="boolean", options={"default": 0})
     */
    private $default;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetimetz")
     */
    private $create_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetimetz")
     */
    private $update_date;

    /**
     * @var integer
     *
     * @ORM\Column(name="del_flg", type="boolean", options={"default": 0})
     */
    private $del_flg;

    /**
     * @return string
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param $ids
     * @return $this
     */
    public function setIds($ids)
    {
        $this->ids = $ids;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getIssueDate()
    {
        return $this->issue_date;
    }

    /**
     * @param $issue_date
     * @return $this
     */
    public function setIssueDate($issue_date)
    {
        $this->issue_date = $issue_date;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage1()
    {
        return $this->message1;
    }

    /**
     * @param $message1
     * @return $this
     */
    public function setMessage1($message1)
    {
        $this->message1 = $message1;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage2()
    {
        return $this->message2;
    }

    /**
     * @param $message2
     * @return $this
     */
    public function setMessage2($message2)
    {
        $this->message2 = $message2;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage3()
    {
        return $this->message3;
    }

    /**
     * @param $message3
     * @return $this
     */
    public function setMessage3($message3)
    {
        $this->message3 = $message3;

        return $this;
    }

    /**
     * @return string
     */
    public function getNote1()
    {
        return $this->note1;
    }

    /**
     * @param $note1
     * @return $this
     */
    public function setNote1($note1)
    {
        $this->note1 = $note1;

        return $this;
    }

    /**
     * @return string
     */
    public function getNote2()
    {
        return $this->note2;
    }

    /**
     * @param $note2
     * @return $this
     */
    public function setNote2($note2)
    {
        $this->note2 = $note2;

        return $this;
    }

    /**
     * @return string
     */
    public function getNote3()
    {
        return $this->note3;
    }

    /**
     * @param $note3
     * @return $this
     */
    public function setNote3($note3)
    {
        $this->note3 = $note3;

        return $this;
    }

    /**
     * @return int
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param $default
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * @param $create_date
     * @return $this
     */
    public function setCreateDate($create_date)
    {
        $this->create_date = $create_date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * @param $update_date
     * @return $this
     */
    public function setUpdateDate($update_date)
    {
        $this->update_date = $update_date;

        return $this;
    }

    /**
     * @return int
     */
    public function getDelFlg()
    {
        return $this->del_flg;
    }

    /**
     * @param $del_flg
     * @return $this
     */
    public function setDelFlg($del_flg)
    {
        $this->del_flg = $del_flg;

        return $this;
    }
}

