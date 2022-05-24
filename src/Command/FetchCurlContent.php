<?php namespace Anomaly\XmlFeedWidgetExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\DashboardModule\Widget\Contract\WidgetInterface;
use Illuminate\Http\Request;

/**
 * Class FetchCurlContent
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class FetchCurlContent
{

    /**
     * The widget instance.
     *
     * @var WidgetInterface
     */
    protected $widget;

    /**
     * Create a new FetchCurlContent instance.
     *
     * @param WidgetInterface $widget
     */
    public function __construct(WidgetInterface $widget)
    {
        $this->widget = $widget;
    }

    /**
     * Handle the command.
     *
     * @param \SimplePie                       $rss
     * @param ConfigurationRepositoryInterface $configuration
     * @return null|\SimplePie_Item[]
     */
    public function handle(\SimplePie $rss, ConfigurationRepositoryInterface $configuration, Request $request)
    {
        // Let Laravel cache everything.
        $rss->enable_cache(false);
        $url = $configuration->value('anomaly.extension.xml_feed_widget::url', $this->widget->getId());
        if ($configuration->value('anomaly.extension.xml_feed_widget::multilingual_feeder', $this->widget->getId())) {
            $locale = $request->session()->get('_locale');
            $url = str_replace('{locale}', $locale, $url);
            $rss->set_feed_url($url);
        } else {
            $rss->set_feed_url(
                $configuration->value(
                    'anomaly.extension.xml_feed_widget::url',
                    $this->widget->getId(),
                    'http://pyrocms.com/posts/rss.xml'
                )
            );
        }
        // Make the request.
        $rss->init();

        return $rss->get_items(0, 5);
    }
}
