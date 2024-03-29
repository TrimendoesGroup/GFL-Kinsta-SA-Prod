<?php

N2Loader::import('libraries.plugins.N2SliderItemAbstract', 'smartslider');

class N2SSPluginItemHeading extends N2SSPluginItemAbstract {

    var $_identifier = 'heading';

    protected $priority = 'div';

    private static $font = 1009;

    protected $group = 'Basic';

    public function __construct() {
        $this->_title = n2_x('Heading', 'Slide item');
    }

    private static function initDefaultFont() {
        static $inited = false;
        if (!$inited) {
            $res = N2StorageSectionAdmin::get('smartslider', 'default', 'item-heading-font');
            if (is_array($res)) {
                self::$font = $res['value'];
            }
            if (is_numeric(self::$font)) {
                N2FontRenderer::preLoad(self::$font);
            }
            $inited = true;
        }
    }

    private static $style = '';

    private static function initDefaultStyle() {
        static $inited = false;
        if (!$inited) {
            $res = N2StorageSectionAdmin::get('smartslider', 'default', 'item-heading-style');
            if (is_array($res)) {
                self::$style = $res['value'];
            }
            if (is_numeric(self::$style)) {
                N2StyleRenderer::preLoad(self::$style);
            }
            $inited = true;
        }
    }

    public static function onSmartsliderDefaultSettings(&$settings) {
        self::initDefaultFont();
        $settings['font'][] = '<param name="item-heading-font" type="font" previewmode="hover" label="' . n2_('Item') . ' - ' . n2_('Heading') . '" default="' . self::$font . '" />';

        self::initDefaultStyle();
        $settings['style'][] = '<param name="item-heading-style" type="style" set="heading" previewmode="heading" label="' . n2_('Item') . ' - ' . n2_('Heading') . '" default="' . self::$style . '" />';
    }

    function getTemplate($slider) {

        return "<div><h2 id='{uid}' class='{fontclass} {styleclass} {class} n2-ow' style='display: {display}; {extrastyle};'><a style='display: {display};' href='#' class='{afontclass} {astyleclass}' onclick='return false;'>{heading}</a></h2>" . N2Html::scriptTemplate($this->getJs($slider->elementId, "{uid}")) . "</div>";
    }

    function getJs($sliderId, $id) {
        return '
            if(typeof window.ssitemmarker == "undefined" && "{splittextmode}" != 0){
                new NextendSmartSliderHeadingItemSplitTextAdmin(window["' . $sliderId . '"], \'' . $id . '\', "{splitTextTransformOrigin}", "{splitTextBackfaceVisibility}", "{splitTextIn}", {splitTextDelayIn}, "{splitTextOut}", {splitTextDelayOut});
            }';
    }

    function _render($data, $itemId, $slider, $slide) {
        return $this->getHtml($data, $itemId, $slider, $slide);
    }

    function _renderAdmin($data, $itemId, $slider, $slide) {
        return $this->getHtml($data, $itemId, $slider, $slide);
    }

    private function getHtml($data, $id, $slider, $slide) {
        $attributes = array();
        $inDelay  = $data->get('split-text-delay-in', 0) / 1000;
        $outDelay = $data->get('split-text-delay-out', 0) / 1000;

        $in  = $data->get('split-text-animation-in', '');
        $out = $data->get('split-text-animation-out', '');

        $transformOrigin    = implode('% ', explode('|*|', $data->get('split-text-transform-origin', '50|*|50|*|0'))) . 'px';
        $backfaceVisibility = $data->get('split-text-backface-visibility', 1) ? 'visible' : 'hidden';

        if (!empty($in) || !empty($out)) {
            if ($this->isEditor) {
                $slider->features->addInitCallback('new NextendSmartSliderHeadingItemSplitTextAdmin(arguments[0], "' . $id . '", "' . $transformOrigin . '", "' . $backfaceVisibility . '",  "' . $in . '",' . $inDelay . ', "' . $out . '", ' . $outDelay . ');');
            } else {

                if (!empty($in)) {
                    $in = base64_decode($in);
                } else {
                    $in = 'false';
                }
                if (!empty($out)) {
                    $out = base64_decode($out);
                } else {
                    $out = 'false';
                }

                $slider->features->addInitCallback('new NextendSmartSliderHeadingItemSplitText(arguments[0], "' . $id . '", "' . $transformOrigin . '", "' . $backfaceVisibility . '",  ' . $in . ',' . $inDelay . ', ' . $out . ', ' . $outDelay . ');');
            }
        }
    

        $font  = N2FontRenderer::render($data->get('font'), 'hover', $slider->elementId, 'div#' . $slider->elementId . ' .n2-ss-layer ', $slider->fontSize);
        $style = N2StyleRenderer::render($data->get('style'), 'heading', $slider->elementId, 'div#' . $slider->elementId . ' ');

        $linkAttributes = array();
        if ($this->isEditor) {
            $linkAttributes['onclick'] = 'return false;';
        }

        $title = $data->get('title', '');
        if (!empty($title)) {
            $attributes['title'] = $title;
        }

        list($link) = (array)N2Parse::parse($data->get('link', '#|*|'));
        if (!empty($link) && $link != '#') {
            $linkAttributes['class'] = $font . $style . ' n2-ow';

            $font  = '';
            $style = '';
        } else {
            $linkAttributes['class'] = ' n2-ow';
        }
        $linkAttributes['style'] = "display:" . ($data->get('fullwidth', 1) ? 'block' : 'inline-block') . ";";

        return $this->heading($data->get('priority', 'div'), $attributes + array(
                "id"    => $id,
                "class" => $font . $style . " " . $data->get('class', '') . ' n2-ow',
                "style" => "display:" . ($data->get('fullwidth', 1) ? 'block' : 'inline-block') . ";" . ($data->get('nowrap', 0) ? 'white-space:nowrap;' : '')
            ), $this->getLink($slide, $data, str_replace("\n", '<br />', strip_tags($slide->fill($data->get('heading', '')))), $linkAttributes));
    }

    private function heading($type, $attributes, $content) {
        if ($type > 0) {
            return N2Html::tag("h{$type}", $attributes, $content);
        }
        return N2Html::tag("div", $attributes, $content);
    }

    function getValues() {
        self::initDefaultFont();
        self::initDefaultStyle();
        return array(
            'priority'  => 'div',
            'fullwidth' => 1,
            'nowrap'    => 0,
            'heading'   => n2_('Heading layer'),
            'title'     => '',
            'link'      => '#|*|_self',
            'font'      => self::$font,
            'style'     => self::$style,

            'split-text-transform-origin'    => '50|*|50|*|0',
            'split-text-backface-visibility' => 1,

            'split-text-animation-in' => '',
            'split-text-delay-in'     => 0,

            'split-text-animation-out' => '',
            'split-text-delay-out'     => 0,

            'class' => ''
        );
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->_identifier . DIRECTORY_SEPARATOR;
    }

    public function getFilled($slide, $data) {
        $data->set('heading', $slide->fill($data->get('heading', '')));
        $data->set('link', $slide->fill($data->get('link', '#|*|')));
        return $data;
    }

    public function prepareExport($export, $data) {
        $export->addVisual($data->get('font'));
        $export->addVisual($data->get('style'));
        $export->addLightbox($data->get('link'));
    }

    public function prepareImport($import, $data) {
        $data->set('font', $import->fixSection($data->get('font')));
        $data->set('style', $import->fixSection($data->get('style')));
        $data->set('link', $import->fixLightbox($data->get('link')));
        return $data;
    }

    public function prepareFixed($data) {
        $data->set('link', $this->fixLightbox($data->get('link')));
        return $data;
    }

}

N2Plugin::addPlugin('ssitem', 'N2SSPluginItemHeading');

N2Pluggable::addAction('smartsliderDefault', 'N2SSPluginItemHeading::onSmartsliderDefaultSettings');

