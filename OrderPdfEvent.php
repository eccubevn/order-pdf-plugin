<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\OrderPdf;

use Eccube\Event\TemplateEvent;
use Plugin\OrderPdf\Event\OrderPdf;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class OrderPdfEvent.
 */
class OrderPdfEvent implements EventSubscriberInterface
{
    /** @var OrderPdf */
    protected $orderPdfEvent;

    /**
     * OrderPdfEvent constructor.
     *
     * @param OrderPdf $orderPdf
     */
    public function __construct(OrderPdf $orderPdf)
    {
        $this->orderPdfEvent = $orderPdf;
    }

    /**
     * Event for new hook point.
     *
     * @param TemplateEvent $event
     */
    public function onAdminOrderIndexRender(TemplateEvent $event)
    {
        /* @var OrderPdf $orderPdfEvent */
        $this->orderPdfEvent->onAdminOrderIndexRender($event);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Admin/@admin/Order/index.twig' => [['onAdminOrderIndexRender', 10]],
        ];
    }
}
