<?php


class N2SmartsliderBackendSlidersView extends N2ViewBase {

    public function getDashboardButtons() {

        $app        = N2Base::getApplication('smartslider');
        $accessEdit = N2Acl::canDo('smartslider_edit', $app->info);

        $buttons = '';
        if ($accessEdit) {

            $buttons .= N2Html::tag('a', array(
                'data-label' => n2_('Import slider'),
                'href'       => $app->router->createUrl(array('sliders/import')),
                'id'         => 'n2-ss-import-slider'
            ), N2Html::tag('i', array('class' => 'n2-i n2-i-a-import')));

        }
        $updateModel = N2SmartsliderUpdateModel::getInstance();
        $hasUpdate   = $updateModel->hasUpdate();
        $this->appType->router->setMultiSite();
        $updateUrl = $this->appType->router->createUrl(array(
            'update/update',
            N2Form::tokenizeUrl() + array('download' => 1)
        ));
        $this->appType->router->unSetMultiSite();


        $buttons .= N2Html::tag('a', array(
            'data-label' => n2_('Check for updates'),
            'href'       => $app->router->createUrl(array(
                'update/check',
                N2Form::tokenizeUrl()
            )),
            'id'         => 'n2-ss-check-update',
        ), N2Html::tag('i', array('class' => 'n2-i n2-i-a-refresh')));


        if ($hasUpdate) {
            ?>
            <script type="text/javascript">
                    n2(window).ready(function ($) {
                        $('.n2-main-top-bar').append('<div class="n2-left n2-top-bar-menu"><span><?php printf(n2_('Version %s available!'), $updateModel->getVersion()); ?></span> <a style="font-size: 12px;margin-right: 10px;" class="n2-h3 n2-uc n2-button n2-button-normal n2-button-blue n2-button-m n2-radius-s" href="<?php echo $updateUrl; ?>"><?php n2_e('Update'); ?></a> <a style="font-size: 12px;" class="n2-h3 n2-uc n2-button n2-button-normal n2-button-grey n2-button-m n2-radius-s" href="#" onclick="NextendModalDocumentation(\'<?php n2_e('Changelog'); ?>\', \'http://doc.smartslider3.com/article/432-changelog?utm_campaign=<?php echo N2SS3::$campaign; ?>&utm_source=changelog&utm_medium=smartslider-' + N2PLATFORM + '-' + (N2SSPRO ? 'pro' : 'free') + '\');return false;"><?php n2_e('Changelog'); ?></a></div>');
                    });
                </script>
            <?php
        }
        $licenseModel = N2SmartsliderLicenseModel::getInstance();
        $class1       = '';
        $class2       = '';
        if ($licenseModel->hasKey()) {
            $class1 = ' n2-ss-license-has-active-key ';
        } else {
            $class2 = ' n2-ss-license-no-active-key ';
        }


        $buttons .= N2Html::tag('a', array(
            'data-label' => n2_('Add license'),
            'href'       => '#',
            'id'         => 'n2-ss-add-license',
            'class'      => 'n2-box-add-license ' . $class1,
            'onclick'    => "addLicense();return false;"
        ), N2Html::tag('i', array('class' => 'n2-i n2-i-a-license')));

        $buttons .= N2Html::tag('a', array(
            'data-label' => n2_('Deauthorize license'),
            'href'       => $app->router->createUrl(array('license/deauthorize')),
            'id'         => 'n2-ss-deauthorize-license',
            'class'      => 'n2-box-license-activated ' . $class2
        ), N2Html::tag('i', array('class' => 'n2-i n2-i-a-deauthorize')));

    
    


        return $buttons;
    }
} 