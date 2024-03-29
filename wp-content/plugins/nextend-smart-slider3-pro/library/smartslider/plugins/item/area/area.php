<?php
N2Loader::import('libraries.plugins.N2SliderItemAbstract', 'smartslider');

class N2SSPluginItemArea extends N2SSPluginItemAbstract {

    public $_identifier = 'area';

    protected $priority = 100;

    protected $layerProperties = array(
        "width"  => 150,
        "height" => 150
    );

    protected $group = 'Advanced';

    public function __construct() {
        $this->_title = n2_x('Area', 'Slide item');
    }

    public function getTemplate($slider) {

        return '<span style="display:inline-block;vertical-align:top;width:{width};height:{height};background-color:{colora};opacity:{opacity};border:{borderWidth} solid {borderColora};border-radius:{borderRadius};-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;{css}"></span>';
    }

    function _renderAdmin($data, $itemId, $slider, $slide) {
        return $this->getHtml($data);
    }

    function _render($data, $itemId, $slider, $slide) {

        return $this->getLink($slide, $data, $this->getHtml($data), array(
            'style' => 'display: block; width:100%;height:100%;',
            'class' => 'n2-ow'
        ));
    }

    private function getHtml($data) {
        $width  = '100%';
        $height = '100%';

        $_width = $data->get('width');
        if (!empty($_width)) {
            $width = $_width . 'px';
        }

        $_height = $data->get('height');
        if (!empty($_height)) {
            $height = $_height . 'px';
        }

        $css = $data->get('css');

        $style = '';

        $color = $data->get('color');

        $gradient = $data->get('gradient', 'off');

        if ($gradient != 'off') {
            $colorEnd = $data->get('color2');
            switch ($gradient) {
                case 'horizontal':
                    $style .= 'background:#' . substr($color, 0, 6) . ';';
                    $style .= 'background:-moz-linear-gradient(left, ' . N2Color::colorToRGBA($color) . ' 0%,' . N2Color::colorToRGBA($colorEnd) . ' 100%);';
                    $style .= 'background:-webkit-linear-gradient(left, ' . N2Color::colorToRGBA($color) . ' 0%,' . N2Color::colorToRGBA($colorEnd) . ' 100%);';
                    $style .= 'background:linear-gradient(to right, ' . N2Color::colorToRGBA($color) . ' 0%,' . N2Color::colorToRGBA($colorEnd) . ' 100%);';
                    $style .= 'background:filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#' . substr($color, 0, 6) . '\', endColorstr=\'#' . substr($color, 0, 6) . '\',GradientType=1);';
                    break;
                case 'vertical':
                    $style .= 'background:#' . substr($color, 0, 6) . ';';
                    $style .= 'background:-moz-linear-gradient(top, ' . N2Color::colorToRGBA($color) . ' 0%,' . N2Color::colorToRGBA($colorEnd) . ' 100%);';
                    $style .= 'background:-webkit-linear-gradient(top, ' . N2Color::colorToRGBA($color) . ' 0%,' . N2Color::colorToRGBA($colorEnd) . ' 100%);';
                    $style .= 'background:linear-gradient(to bottom, ' . N2Color::colorToRGBA($color) . ' 0%,' . N2Color::colorToRGBA($colorEnd) . ' 100%);';
                    $style .= 'background:filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#' . substr($color, 0, 6) . '\', endColorstr=\'#' . substr($color, 0, 6) . '\',GradientType=0);';
                    break;
                case 'diagonal1':
                    $style .= 'background:#' . substr($color, 0, 6) . ';';
                    $style .= 'background:-moz-linear-gradient(45deg, ' . N2Color::colorToRGBA($color) . ' 0%,' . N2Color::colorToRGBA($colorEnd) . ' 100%);';
                    $style .= 'background:-webkit-linear-gradient(45deg, ' . N2Color::colorToRGBA($color) . ' 0%,' . N2Color::colorToRGBA($colorEnd) . ' 100%);';
                    $style .= 'background:linear-gradient(45deg, ' . N2Color::colorToRGBA($color) . ' 0%,' . N2Color::colorToRGBA($colorEnd) . ' 100%);';
                    $style .= 'background:filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#' . substr($color, 0, 6) . '\', endColorstr=\'#' . substr($color, 0, 6) . '\',GradientType=1);';
                    break;
                case 'diagonal2':
                    $style .= 'background:#' . substr($color, 0, 6) . ';';
                    $style .= 'background:-moz-linear-gradient(-45deg, ' . N2Color::colorToRGBA($color) . ' 0%,' . N2Color::colorToRGBA($colorEnd) . ' 100%);';
                    $style .= 'background:-webkit-linear-gradient(-45deg, ' . N2Color::colorToRGBA($color) . ' 0%,' . N2Color::colorToRGBA($colorEnd) . ' 100%);';
                    $style .= 'background:linear-gradient(-45deg, ' . N2Color::colorToRGBA($color) . ' 0%,' . N2Color::colorToRGBA($colorEnd) . ' 100%);';
                    $style .= 'background:filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#' . substr($color, 0, 6) . '\', endColorstr=\'#' . substr($color, 0, 6) . '\',GradientType=1);';
                    break;
            }
        } else {
            if (strlen($color) == 8 && substr($color, 6, 2) != '00') {
                $style = 'background-color: #' . substr($color, 0, 6) . ';';
                $style .= "background-color: " . N2Color::colorToRGBA($color) . ";";
            }
        }

        $borderWidth = max(0, intval($data->get('borderWidth')));
        list($borderHex, $borderRgba) = N2Color::colorToCss($data->get('borderColor'));
        $borderRadius = max(0, intval($data->get('borderRadius')));

        return N2Html::tag('span', array(
            'style' => 'display:inline-block;vertical-align:top;width:' . $width . ';height:' . $height . ';' . $style . 'border:' . $borderWidth . 'px solid #' . $borderHex . ';border:' . $borderWidth . 'px solid ' . $borderRgba . ';border-radius:' . $borderRadius . 'px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;' . $css
        ));
    }

    public function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->_identifier . DIRECTORY_SEPARATOR;
    }

    function getValues() {
        return array(
            'width'        => '',
            'height'       => '',
            'color'        => '000000ff',
            'gradient'     => 'off',
            'color2'       => '000000ff',
            'css'          => '',
            'borderWidth'  => 0,
            'borderColor'  => 'ffffff1f',
            'borderRadius' => 0,
            'link'         => '#|*|_self'
        );
    }

    public function prepareExport($export, $data) {
        $export->addLightbox($data->get('link'));
    }

    public function prepareImport($import, $data) {
        $data->set('link', $import->fixLightbox($data->get('link')));
        return $data;
    }

    public function prepareFixed($data) {
        $data->set('link', $this->fixLightbox($data->get('link')));
        return $data;
    }
}

N2Plugin::addPlugin('ssitem', 'N2SSPluginItemArea');
