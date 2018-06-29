<?php
/*
 * This file is part of the Order Pdf plugin
 *
 * Copyright (C) 2016 LOCKON CO.,LTD. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\OrderPdf;

use Eccube\Event\EventArgs;
use Eccube\Event\TemplateEvent;
use Plugin\OrderPdf\Event\OrderPdf;
use Plugin\OrderPdf\Event\OrderPdfLegacy;
use Plugin\OrderPdf\Util\Version;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Class OrderPdfEvent.
 */
class OrderPdfEvent implements EventSubscriberInterface
{
    /** @var  OrderPdf */
    protected $orderPdfEvent;

    /**
     * OrderPdfEvent constructor.
     * @param OrderPdf $orderPdf
     */
    public function __construct(OrderPdf $orderPdf)
    {
        $this->orderPdfEvent = $orderPdf;
    }

    public function onAdminOrderIndexInitialize(EventArgs $event)
    {
        /* @var OrderPdf $orderPdfEvent */
//        $this->orderPdfEvent->onAdminOrderIndexInitialize($event);
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
     * Event for v3.0.0 - 3.0.8.
     *
     * @param FilterResponseEvent $event
     *
     * @deprecated for since v3.0.0, to be removed in 3.1
     */
    public function onRenderAdminOrderPdfBefore(FilterResponseEvent $event)
    {
        if ($this->supportNewHookPoint()) {
            return;
        }

        /* @var OrderPdfLegacy $eventLegacy */
        $eventLegacy = $this->app['orderpdf.event.order_pdf_legacy'];
        $eventLegacy->onRenderAdminOrderPdfBefore($event);
    }

    /**
     * Check to support new hookpoint.
     *
     * @return bool v3.0.9以降のフックポイントに対応しているか？
     */
    private function supportNewHookPoint()
    {
        return Version::isSupportVersion();
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'admin.order.index.initialize' => [['onAdminOrderIndexInitialize', 10]],
            'Admin/@admin/Order/index.twig' => [['onAdminOrderIndexRender', 10]],
        ];
    }
}
