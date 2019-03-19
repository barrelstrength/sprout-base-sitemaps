<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasesitemaps\controllers;


use barrelstrength\sproutbasesitemaps\models\Settings;
use barrelstrength\sproutbasesitemaps\SproutBaseSitemaps;
use craft\base\Plugin;
use craft\web\Controller;

use Craft;
use yii\web\HttpException;
use yii\web\Response;


/**
 * Class XmlSitemapController
 */
class XmlSitemapController extends Controller
{
    /**
     * @inheritdoc
     */
    public $allowAnonymous = ['render-xml-sitemap'];

    /**
     * Generates an XML sitemapindex or sitemap
     *
     * @param null     $sitemapKey
     * @param int|null $pageNumber
     *
     * @return \yii\web\Response
     * @throws HttpException
     * @throws \craft\errors\SiteNotFoundException
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    public function actionRenderXmlSitemap($sitemapKey = null, int $pageNumber = null): Response
    {
        $siteId = Craft::$app->sites->getCurrentSite()->id;
        $multiSiteSiteIds = [];
        $sitesInGroup = [];

        $currentPluginHandle = Craft::$app->getRequest()->getSegment(1);
        /** @var Plugin $plugin */
        $plugin = Craft::$app->plugins->getPlugin($currentPluginHandle);
        /** @var Settings $pluginSettings */
        $pluginSettings = $plugin->getSettings();
        $isMultilingualSitemap = $pluginSettings->enableMultilingualSitemaps;

        if (Craft::$app->getIsMultiSite() && $isMultilingualSitemap) {
            $sitesInGroup = SproutBaseSitemaps::$app->xmlSitemap->getCurrentSitemapSites();
            $firstSiteInGroup = $sitesInGroup[0];

            // Only render sitemaps for the primary site in a group
            if ($siteId !== $firstSiteInGroup->id) {
                throw new HttpException(404);
            }

            foreach ($sitesInGroup as $siteInGroup) {
                $multiSiteSiteIds[] = (int)$siteInGroup->id;
            }
        }

        $sitemapIndexUrls = [];
        $elements = [];

        switch ($sitemapKey) {
            // Generate Sitemap Index
            case '':
                $sitemapIndexUrls = SproutBaseSitemaps::$app->xmlSitemap->getSitemapIndex($siteId);
                break;

            // Prepare Singles Sitemap
            case 'singles':
                $elements = SproutBaseSitemaps::$app->xmlSitemap->getDynamicSitemapElements('singles', $pageNumber, $siteId);
                break;

            // Prepare Custom Pages Sitemap
            case 'custom-pages':
                if (count($multiSiteSiteIds)) {
                    $elements = SproutBaseSitemaps::$app->xmlSitemap->getCustomSectionUrlsForMultipleIds($multiSiteSiteIds, $sitesInGroup);
                } else {
                    $elements = SproutBaseSitemaps::$app->xmlSitemap->getCustomSectionUrls($siteId);
                }

                break;

            // Prepare URL-Enabled Section Sitemap
            default:
                $elements = SproutBaseSitemaps::$app->xmlSitemap->getDynamicSitemapElements($sitemapKey, $pageNumber, $siteId);
        }

        $headers = Craft::$app->getResponse()->getHeaders();
        $headers->set('Content-Type', 'application/xml');

        $templatePath = Craft::getAlias('@sproutbasesitemaps/templates/');
        Craft::$app->view->setTemplatesPath($templatePath);

        // Render a specific sitemap
        if ($sitemapKey) {
            return $this->renderTemplate('_components/sitemaps/sitemap', [
                'elements' => $elements
            ]);
        }

        // Render the sitemapindex if no specific sitemap is defined
        return $this->renderTemplate('_components/sitemaps/sitemapindex', [
            'sitemapIndexUrls' => $sitemapIndexUrls
        ]);
    }
}
