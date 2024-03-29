<?php
$cmd = N2Request::getVar("nextendcontroller", "sliders");
/**
 * @see Nav
 */

$views = array();
$views[] = N2Html::tag('a', array(
    'href'  => $this->appType->router->createUrl("settings/default"),
    'class' => 'n2-h4 n2-uc ' . ($cmd == "settings" ? "n2-active" : "")
), n2_('Settings'));



$help = N2Html::link(n2_('Docs'), 'http://doc.smartslider3.com?utm_campaign=' . N2SS3::$campaign . '&utm_source=dashboard-documentation&utm_medium=smartslider-' . N2Platform::getPlatform() . '-' . (N2SSPRO ? 'pro' : 'free'), array(
        'target' => '_blank',
        'class'  => 'n2-h4'
    )) . N2Html::link(n2_('Videos'), 'https://www.youtube.com/watch?v=MKmIwHAFjSU&list=PLSawiBnEUNfvzcI3pBHs4iKcbtMCQU0dB&utm_campaign=' . N2SS3::$campaign . '&utm_source=dashboard-watch-videos&utm_medium=smartslider-' . N2Platform::getPlatform() . '-' . (N2SSPRO ? 'pro' : 'free'), array(
        'target' => '_blank',
        'class'  => 'n2-h4'
    )) . N2Html::link(n2_('Support'), 'http://smartslider3.com/contact-us/?utm_campaign=' . N2SS3::$campaign . '&utm_source=dashboard-write-support&utm_medium=smartslider-' . N2Platform::getPlatform() . '-' . (N2SSPRO ? 'pro' : 'free'), array(
        'target' => '_blank',
        'class'  => 'n2-h4'
    )) . N2Html::link(n2_('Newsletter'), 'http://eepurl.com/bDp_8b?utm_campaign=' . N2SS3::$campaign . '&utm_source=dashboard-subscribe-newsletter&utm_medium=smartslider-' . N2Platform::getPlatform() . '-' . (N2SSPRO ? 'pro' : 'free'), array(
        'target' => '_blank',
        'class'  => 'n2-h4'
    ));

$views[] = N2Html::tag('div', array(
    'class' => 'n2-menu-has-sub'
), N2Html::tag('span', array('class' => 'n2-uc'), n2_('Help')) . N2Html::tag('div', array('class' => 'n2-menu-sub'), $help));

$this->widget->init('nav', array(
    'logoUrl'      => $this->appType->router->createUrl("sliders/index"),
    'logoImageUrl' => $this->appType->app->getLogo(),
    'views'        => $views,
    'actions'      => $this->getFragmentValue('actions')
));
?>