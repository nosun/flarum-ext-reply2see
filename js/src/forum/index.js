import {extend} from 'flarum/common/extend';
import app from 'flarum/common/app';
import CommentPost from 'flarum/common/components/CommentPost';
import DiscussionPage from 'flarum/common/components/DiscussionPage';
import DiscussionControls from 'flarum/common/utils/DiscussionControls';
import LogInModal from 'flarum/common/components/LogInModal';


app.initializers.add('nosun/reply-to-see', () => {
	extend(CommentPost.prototype, 'content', function()
	{
	    if (app.session.user && app.current instanceof DiscussionPage)
	        $('.reply2see_reply').off('click').on('click',
	            () => DiscussionControls.replyAction.call(app.current.discussion, true, false)
	        );
	    else
	        $('.reply2see_reply').off('click').on('click',
	            () => app.modal.show(LogInModal)
	        );
	});
});
