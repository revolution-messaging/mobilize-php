<?php

namespace Revmsg\Mobilize;

class Subscription extends Entity\Entity
{
    protected $scheme = 'Revmsg\Mobilize\Model\Subscription';
    protected $customMap = array(
        'v2' => array(
            'unsubscribe' => array(
                'url' => '/api/v1/subscriber/unsubscribe',
                'method' => 'POST',
                'payload' => array(
                    'model' => 'SubscribersAndList'
                    )
                )
            )
        );
    public function unsubscribe ($subscribers, $list)
    {
        if (is_object($subscribers) && strpos(get_class($subscribers), 'Subscriber')) {
            $ids = array(
                $subscribers->getVariable('id')
                );
        } elseif (is_array($subscribers)) {
            $ids = array();
            foreach ($subscribers as $subscriber) {
                if (is_object($subscriber) && strpos(get_class($subscriber), 'Subscriber')) {
                    $ids[] = $subscriber->id;
                } elseif (is_string($subscriber)) {
                    $ids[] = $subscriber;
                }
            }
        } elseif (is_string($subscribers)) {
            $ids = array($subscribers);
        }
        print get_class($list);
        if (is_object($list) && strpos(get_class($list), 'Sublist')) {
            $list = $list->getVariable('id');
        }
        $this->set('SubscribersAndList', array(
            'subscribers' => $ids,
            'list' => $list
            ))->operation('unsubscribe', 'v2');
    }
}
