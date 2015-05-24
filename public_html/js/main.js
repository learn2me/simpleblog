/**
 * Data processor for AJax call
 * The function send Ajax request to insert new comment
 * @param	data	mixed	data returned by the server
 * @param	status	string	response sstatus (server status:success,error. JQuery status: parseerror)
 * @param	xhr		object	XHTTPRequest object
 *  
 */
function addCommentProcessor(data,status,xhr){
	switch(status){
	case "success" :
	
		// Get the comment template from current page and populate it
		var content = $("#add-comment-form textarea[name=comment]").val();
		var template = String($("#sb-comment-template .sb-comment-container-edit").html());
		template = template.replace("{name}",member.name);
		template = template.replace("{date}",data.date);
		template = template.replace("{idComment}",data.idComment);
		template = template.replace("{idPost}",data.idPost);
		template = template.replace("{formToken}",data.formToken);
		template = template.replace("{formTokenKey}",data.formTokenKey);
		template = template.replace("{content}",data.content);
		
		// If there is comments insert the current comment on the top
		if($(".sb-comments-list .sb-comment-container:eq(0)").length>0){
			$(".sb-comments-list .sb-comment-container:eq(0)").prepend($(template));
		}else{			
			
			// Insert normally into the comments container if no comment for the current post
			$(".sb-comments-list").append($(template));
		}
		
		// Display a success message
		break;
	case "error":
		// Display the message from the server
		alert(data.message);
		break;
	case "parsererror":
		// Display a message if error when parsing json
		alert("Something was wrong with the request!");
		break;
	default :
	
		break;
	}
}

function loadMoreProcessor(data,status,xhr){
	switch(status){
	case "success" :
		var target = data.target;
		if(target=== 'posts') {
			if(data.items && data.items.length>0){
				for(var i=0;i<data.items.length;i++){	
					var item = data.items[i];
					// Get the comment template from current page and populate it
					var template = "";
					if(item.canEdit){
						template = String($("#sb-post-template .sb-post-container-edit").html());
					}else{
						template = String($("#sb-post-template .sb-post-container").html());
					}
					template = template.replace("{authorName}",item.authorName);
					template = template.replace("{prettyDateTime}",item.prettyDateTime);
					template = template.replace("{dateCategory}",item.dateCategory);
					template = template.replace("{dateCategoryLink}",item.dateCategoryLink);
					template = template.replace("{title}",item.title);
					template = template.replace("{formToken}",data.formToken);
					template = template.replace("{formTokenKey}",data.formTokenKey);
					template = template.replace("{excerpt}",item.excerpt);
					template = template.replace("{content}",item.content);
					template = template.replace("{idPost}",item.idPost);					
					template = template.replace("{postId}",item.idPost);					
					template = template.replace("{totalComments}",item.totalComments);						
					template = template.replace("{idPost}",item.idPost);				
					var $template = $(template);
					if(member && member.idMember){
						$template.find("a.sb-loggin").hide();
					}else{
						$template.find("a.sb-logged").hide();
					}
					$(".sb-posts-list").append($template);	
				}
				$("input[data-input-role=csrf-token]").attr({formToken:data.formToken}).val(data.formTokenKey);
				$(".sb-load-more-items").attr("data-current-page",data.currentPage);
			}else{
				$(".sb-load-more-items").hide();
			}
		}else if(target=== 'comments'){
			if(data.items && data.items.length>0){
				for(var i=0;i<data.items.length;i++){	
					var item = data.items[i];
					// Get the comment template from current page and populate it					
					var template = "";
					if(item.canEdit){
						template = String($("#sb-comment-template .sb-comment-container-edit").html());
					}else{
						template = String($("#sb-comment-template .sb-comment-container").html());
					}
					template = template.replace("{idComment}",item.idComment);
					template = template.replace("{idComment}",item.idComment);
					template = template.replace("{idPost}",item.postId);
					template = template.replace("{name}",item.authorName);
					template = template.replace("{date}",item.prettyDateTime);
					template = template.replace("{formToken}",data.formToken);
					template = template.replace("{formTokenKey}",data.formTokenKey);
					template = template.replace("{content}",item.content);
					// If there is comments insert the current comment on the top
					if($(".sb-comments-list .sb-comment-container:last").length>0){
						$(".sb-comments-list .sb-comment-container:last").append($(template));
					}else{			
						
						// Insert normally into the comments container if no comment for the current post
						$(".sb-comments-list").append($(template));
					}
				}
				$("input[data-input-role=csrf-token]").attr({formToken:data.formToken}).val(data.formTokenKey);
				$(".sb-load-more-items").attr("data-current-page",data.currentPage);
			}else{
				$(".sb-load-more-items").hide();
			}
		}
		
		break;
	case "error":
		// Display the message from the server
		alert(data.message);
		break;
	case "parsererror":
		// Display a message if error when parsing json
		alert("Something was wrong with the request!");
		break;
	default :
	
		break;
	}
}

$(document).ready(function(){
	
$("#add-comment-form").on("submit",function(e){
	
		var data = $(this).serialize();
		var idPost = $("#add-comment-form input[name=idPost]").val();
		try{
			$.ajax({
				type:"post",
				url:"/comments/"+idPost,
				dataType:"json",
				data:data,
				error:addCommentProcessor,
				success:addCommentProcessor
			});
		}catch(err){
			alert("ERRRRROR");
		}
		e.preventDefault();
		return false;
	});
	
$(".sb-load-more-items").on("click",function(e){	
		
		var currentPage = $(this).attr("data-current-page");
		var target = $(this).attr("data-target");
		var dateTag = $(this).attr("data-date-tag");
		var idMember = -1;
		var data = {
				currentPage:currentPage,
				target:target,
				date:dateTag
			};
		
		if(member && member.idMember){
			data.idMember = member.idMember;
		}	
		if(target && target==="comments"){
			var idPost = $(this).attr("data-postId");
			data.idPost = idPost;
		}	
		console.log(data);
		console.log(member);
		try{
			$.ajax({
				type:"post",
				url:"/load-more",
				dataType:"json",
				data:data,
				error:loadMoreProcessor,
				success:loadMoreProcessor
			});
		}catch(err){
			alert("ERRRRROR");
		}
		e.preventDefault();
		return false;
	});
})
