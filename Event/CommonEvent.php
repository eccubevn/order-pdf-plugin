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

namespace Plugin\OrderPdf\Event;

/**
 * Class Common Event.
 */
class CommonEvent
{
    /**
     * @var \Twig_Environment
     */
    protected $twigEnvironment;

    /**
     * CommonEvent constructor.
     *
     * @param \Twig_Environment $twigEnvironment
     */
    public function __construct(\Twig_Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
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

        $arrMatch = [];
        preg_match($search, $html, $arrMatch, PREG_OFFSET_CAPTURE);

        if (!isset($arrMatch[4])) {
            return $html;
        }

        $oldHtml = $arrMatch[2][0];

        // First html
        $oldHtmlStartPos = $arrMatch[2][1];
        $firstHalfHtml = substr($html, 0, $oldHtmlStartPos);

        // End html
        $oldHtmlEndPos = $arrMatch[3][1];
        $endHalfHtml = substr($html, $oldHtmlEndPos);

        // New html
        $newHtml = str_replace(
            "<button class=\"btn btn-ec-regular mr-2\">{{ 'admin.order.index.btn_bulk_export'|trans }}</button>",
            "<button class=\"btn btn-ec-regular mr-2\">{{ 'admin.order.index.btn_bulk_export'|trans }}</button>".$part,
            $oldHtml);

        $html = $firstHalfHtml.$newHtml.$endHalfHtml;

        return $html;
    }
}
