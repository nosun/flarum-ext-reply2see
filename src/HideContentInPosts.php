<?php

namespace Nosun\ReplyToSee;

class HideContentInPosts extends FormatContent
{
    public function __invoke($serializer, $model, $attributes)
    {
        if (isset($attributes["contentHtml"])) {

            $newHTML = $attributes["contentHtml"];
            if (strpos($newHTML, '<reply2see>') === false){

                return $attributes;
            }

            $isStartPost = $model['discussion']->first_post_id == $model->id;

            if (!$isStartPost) {
                $newHTML = preg_replace('/<reply2see>(.*?)<\/reply2see>/is', '<div>$1</div>', $newHTML);
                $attributes['contentHtml'] = $newHTML;
                return $attributes;
            }

            $usersModel = $model['discussion']->participants()->get('id');
            $users = [];

            foreach ($usersModel as $user) {
                $users[] = $user->id;
            }

            $replied = !$serializer->getActor()->isGuest() && in_array($serializer->getActor()->id, $users);

            if ($replied)
                $newHTML = preg_replace('/<reply2see>(.*?)<\/reply2see>/is', '<div class="reply2see"><div class="reply2see_title">' . $this->translator->trans('wiseclock-reply2see.forum.hidden_content') . '</div>$1</div>', $newHTML);
            else
                $newHTML = preg_replace('/<reply2see>(.*?)<\/reply2see>/is', '<div class="reply2see"><div class="reply2see_alert">' . $this->translator->trans('wiseclock-reply2see.forum.reply_to_see', array('{reply}' => '<a class="reply2see_reply">' . $this->translator->trans('core.forum.discussion_controls.reply_button') . '</a>')) . '</div></div>', $newHTML);

            $attributes['contentHtml'] = $newHTML;
        }

        return $attributes;
    }

}
