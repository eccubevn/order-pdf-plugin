<?php
/*
 * This file is part of the OrderPdf plugin
 *
 * Copyright (C) 2016 LOCKON CO.,LTD. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\OrderPdf\Event;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Translation\TranslatorInterface;


/**
 * Class Common Event.
 */
class CommonEvent
{
    /**
     * @var string target render on the front-end
     */
    protected $makerTag = '<!--# maker-plugin-tag #-->';

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Twig_Environment
     */
    protected $twigEnvironment;

    /**
     * CommonEvent constructor.
     *
     * @param \Twig_Environment $twigEnvironment
     * @param TranslatorInterface $translator
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(
        \Twig_Environment $twigEnvironment,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    /**
     * Render position.
     *
     * @param string $html
     * @param string $part
     *
     * @return string
     */
    public function renderPosition($html, $part)
    {
        /**
         * For old and new ec-cube version
         * Search group
         * Group 1
         * Points to start the search.
         */
        $search = '/(<input\s+type="hidden[\s\S]*)';
        /*
         * Group 2
         * Start div section.
         */
        $search .= '(<div\s+class="col\-auto d\-none btn\-bulk\-wrapper[\s\S]*)';
        /*
         * Group 3
         * The end of the button section.
         */
        $search .= '(<button\s+type="button"\s+class="btn btn\-ec\-delete[\s\S]*)';
        /*
         * Group 4
         * Points to end the search.
         */
        $search .= '(<div\s+class="card rounded border\-0 mb\-4")/';

        $arrMatch = array();
        preg_match($search, $html, $arrMatch, PREG_OFFSET_CAPTURE);

        if (!isset($arrMatch[4])) {
            return $html;
        }

        $oldHtml = $arrMatch[2][0];

        // first html
        $oldHtmlStartPos = $arrMatch[2][1];
        $firstHalfHtml = substr($html, 0, $oldHtmlStartPos);

        // end html
        $oldHtmlEndPos = $arrMatch[3][1];
        $endHalfHtml = substr($html, $oldHtmlEndPos);

        // new html
        $newHtml = str_replace(
            "<button class=\"btn btn-ec-regular mr-2\">{{ 'admin.order.index.btn_bulk_export'|trans }}</button>",
            "<button class=\"btn btn-ec-regular mr-2\">{{ 'admin.order.index.btn_bulk_export'|trans }}</button>".$part,
            $oldHtml);

        $html = $firstHalfHtml.$newHtml.$endHalfHtml;

        return $html;
    }
}
