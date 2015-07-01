<!DOCTYPE html>
<html>
<body>
    <head>
        <title>Ziffi Reviews Widget</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <!--<script src="http://code.jquery.com/jquery-2.1.1-rc2.min.js" ></script>-->
        <script src="jquery-1.6.4.min.js" ></script>
    </head>
    <div id="add_review_form_div">
    <form action="post.php" method="post" name="frm_add_review" id="frm_add_review" class="basic-grey">
        <h1>Your Review
            <span>Please fill all the texts in the fields.</span>
        </h1>
        <label>
            <span>Your Name :</span>
            <input id="name" type="text" name="name" placeholder="Your Full Name" value='Amol Bhausaheb Ikke'/>
        </label>

        <label>
            <span>Your Email :</span>
            <input id="email" type="email" name="email" placeholder="Valid Email Address" value='amol.ikke@ziffi.com'/>
        </label>
       <label>
            <span>Title of your Review:</span>
            <input id="review_title" type="text" name="review_title" placeholder="Your Review Title" value="Awesome"></textarea>
        </label>
        <label>
            <span>Your review about the hotel (*min 50 characters) :</span>
            <textarea id="review_txt" name="review_txt" placeholder="Your Review">The service was awesome</textarea>
        </label>
        <div id="review_contexts_div">
            <center><b>Loading... Please wait...</b></center><br><br>
          <!--<label>Ambience</label>
          <select name="review_info[0][rating]">
              <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>
          </select><br><input type='text' name='review_info[0][review_txt]' value='awesome' />
          <input type="hidden" name="review_info[0][review_context_id]" value='1' />
          <input type="hidden" name="review_info[0][object_id]" value='49' />-->
        </div>
        <input type="hidden" name="reviewed_by" value="482744"/>
        <input type="hidden" name="appointment_id" value="101"/>
         <label>
            <span>&nbsp;</span>
            <input type="submit" class="button" value="Submit" />
        </label>    
    </form>
  </div>
<div id="reviews_container" class="reviews-container">
    <h1>&nbsp;&nbsp;&nbsp;Pending Reviews</h1>
    <div id="pending_reviews_div" class="pending_reviews_div">      
    </div>    
    
    <div id="object_details" class="object-details">
        <b>Aura Thai Spa</b><br><i> Andheri East.</i>
    </div>
    
    <div id="overall_ratings_div" class="overall-ratings-div">
        Overall Ratings: <span id="overall_ratings_span"></span><br><br>
        <span id="total_reviews_count_span"></span>&nbsp;reviews
        <div id="context_wise_ratings">
            <label>
                <span>Context 1:&nbsp;</span><span>4.3</span>
            </label><br>
            <label>
                <span>Context 2:&nbsp;</span><span>2</span>
            </label>
        </div>        
    </div>
    <br>
    <!--<div id="reviews_filter">
        <a href="javascript:void();">All Reviews(<span id="all_reviews_count_span"></span>)</a> | <a href="javascript:void();">Positive Reviews</a> | <a href="javascript:void();">Critical Reviews</a> |  <a href="javascript:void();">Reviews With Images</a>
    </div>-->
    <br>
    <div id="all_reviews_div">
      <!--  <center><b>Loading... Please wait...</b></center><br><br>
      <div id="review_1" class="review_details_div">
      <span>Ratings: &nbsp;</span><span>4/5</span>
      &nbsp;<span>Booked With Ziffi? &nbsp;</span><span>Yes</span>
      <br><span id="reviewer_name">Amol Bhausaheb Ikke</span>
      <br><span id="reviewed_at">2 days ago</span>
      <br>
      <div id="review_text_body">
          <span>Review Title</span><br>
          <span>Review Body Text</span><br>
          <span>Context1: rating</span> | <span>Context2: rating</span> | <span>Context2: rating</span> | <span>Context2: rating</span>
      </div>
      <br>
      <div id="replies" style="margin-left:10%; margin-bottom: 5px;">
          Replies:<br><br>
          <div id="reply_1">
              I second that.
          </div>
          <div id="reply_2">
              I disagree with you.
          </div>
      </div> 
      </div>-->        
    </div>
</div>
</body>
<script type="text/javascript">

$.urlParam = function(name){
    var results = new RegExp('[\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.href);
    return results[1] || 0;
}
  var object_id = 49, object_type = 11, reviewer_id = 482744;
  //var object_id = $.urlParam('object_id'), object_type = $.urlParam('object_type'), reviewer_id = $.urlParam('reviewer_id');
  var overall_reviews_context_id_for_current_object = "";
$(document).ready( function() {
    console.log("Get overall ratings");
    get_reviews_context(object_type);
    get_overall_ratings(object_id);
    get_all_reviews_for_object(object_id, object_type);
    get_pending_reviews();
    $("#frm_add_review").submit(function(e){
      e.preventDefault();
      add_review();
    });
});


function get_all_reviews_for_object(object_id, object_type) {
  $("#all_reviews_div").html("<center><b>Loading... Please wait...</b></center><br><br>");
    $.ajax({
        url: "http://www.liffi.com/api/reviews/get/",
        type: "post",
        dataType: "json",
        data: {"object_id": object_id, "object_type": object_type},
        success: function(response) {
          if (response.reviews == undefined) {
            $("#all_reviews_div").html("No reviews found.");
            return false;
          }
            var all_reviews_html = "";
            $("#all_reviews_div").html("");
            $.each(response.reviews, function(review_id, review_details) {
                console.log("index: " + review_id + " | reviewer: " + review_details.reviewed_by_name);
                var review_txt = "", overall_reviews_context_link_id = "";
                all_reviews_html += "<br><div id='review_"+review_id+"' class='review_details_div'>";
                //all_reviews_html += "<span style='float:right;'><a href='javascript:void(0);' onclick='publish("+review_details.review_id+"," + review_details.reviewed_by + ");' class='publish_review_link'>Publish</a></span>";
                all_reviews_html += "<span style='float:right;'><a href='javascript:void(0);' onclick='delete_review("+review_details.review_id+"," + review_details.reviewed_by + ");' class='publish_review_link'>Delete</a></span>";
                all_reviews_html += "<span><b>Ratings:</b> &nbsp;</span><span>4/5</span>&nbsp;<span>  |  <b>Booked With Ziffi?</b> &nbsp;</span><span>" + (review_details.appointment_id > 0 ? "Yes" : "No") + "</span><br><span id='reviewer_name' style='color:#0c65a5'><em>"+review_details.reviewed_by_name+"</em></span><span id='reviewed_at' style='color:#999'>" + review_details.reviews_created_at + "</span><br><br><div id='review_text_body'><span id='review_title_span_"+review_details.review_id+"'></span><div id='review_text_p_"+review_details.review_id+"' class='review_txt_div'><em>Review Body Text</em></div></div><br>";
                $.each(review_details.review_info, function(review_context_link_id, review_context_rating_details){
                    console.log("review_context_link_id: " + review_context_link_id + " | reviews_context_text: " + review_context_rating_details.reviews_context_text.toLowerCase() + " | review_txt: " + review_context_rating_details.review_txt);
                    all_reviews_html += "<span><em>" + review_context_rating_details.reviews_context_text + "</em> (" + review_context_rating_details.rating + "/5)</span><br>";
                    if (review_context_rating_details.reviews_context_text.toLowerCase() == 'overall') {
                      review_txt = review_context_rating_details.review_txt;
                      overall_reviews_context_link_id = review_context_rating_details.reviews_context_link_id;
                    }
                    if (review_context_rating_details.hasOwnProperty("replies")) {
                        all_reviews_html += "<div id='replies' style='margin-left:2%; margin-bottom: 5px;'><br><em>Replies:</em><br>"
                        $.each(review_context_rating_details.replies, function(reply_reviews_context_link_id, reply_details) {
                            console.log("reply_reviews_context_link_id: " + reply_reviews_context_link_id + " | reviews_context_text: " + reply_details.reviews_context_text + " | review_txt: " + reply_details.review_txt);
                            all_reviews_html += "<div id='reply_" + reply_reviews_context_link_id + "'>" + "<span style='margin-left:1%;'>" + reply_details.review_txt + "</span> - <span style='color:#0c65a5'>" + reply_details.reviews_context_link_created_by_name + "</span>&nbsp;&nbsp;<span style='color:#999'>" + reply_details.reviews_context_link_created_at + " | Replied by manager: " + reply_details.is_replied_by_manager + "</span><span style='float:right;'><a href='javascript:void();' onclick='publish("+reply_details.reviews_context_link_id+","+reviewer_id+",1);' style='color:white;'>Publish</a>&nbsp; / &nbsp;<a href='javascript:void();' onclick='delete_review("+reply_details.reviews_context_link_id+","+reviewer_id+",1);' style='color:white;'>Delete</a></span></div>";
                        });
                        all_reviews_html += "</div>";    
                    }
                });
                all_reviews_html += "<div id='voting_div_"+review_details.review_id+"' class='voting_div'><span id='upvote_count_span_"+overall_reviews_context_link_id+"' style='margin: 2px;'>&nbsp;"+review_details.upvotes+"&nbsp;</span><a href='javascript:void(0);' onclick=\"vote("+overall_reviews_context_link_id+",'upvote',"+reviewer_id+");\"><img src='like-icon.png' alt='Upvote'></a> | <a href='javascript:void(0);' onclick=\"vote("+overall_reviews_context_link_id+",'downvote',"+reviewer_id+");\"><img src='dislike-icon.png' alt='Downvote'></a><span id='downvote_count_span_"+overall_reviews_context_link_id+"' style='margin: 2px;'>&nbsp;"+review_details.downvotes+"&nbsp;</span> | <a href='javascript:void(0);' alt='Report abuse' class='report_abuse_link' onclick=\"vote("+overall_reviews_context_link_id+",'abuse',"+reviewer_id+");\">Report Abuse</a>&nbsp;|&nbsp;<a href='javascript:void(0);' id='reply_link_"+overall_reviews_context_link_id+"' alt='Reply' class='report_abuse_link' onclick='show_reply_form("+overall_reviews_context_link_id+");'>Reply</a></div>";
                all_reviews_html += "<div id='reply_div_"+overall_reviews_context_link_id+"' style='display:none;'><form name='add_reply_form_"+overall_reviews_context_link_id+"' id='add_reply_form_"+overall_reviews_context_link_id+"'><textarea name='review_txt'></textarea><br><input type='button' name='post_reply_btn_"+overall_reviews_context_link_id+"' id='post_reply_btn_"+overall_reviews_context_link_id+"' value='Post' onclick='reply("+overall_reviews_context_link_id+");'><input type='hidden' name='parent_id' value='"+overall_reviews_context_link_id+"' /></form></div>";
                all_reviews_html += "</div>";
                $("#all_reviews_div").append(all_reviews_html);
                $("#review_title_span_"+review_details.review_id).html("<b>Overall</b>");
                $("#review_text_p_"+review_details.review_id).html("<em>" + review_txt + "</em>");
                all_reviews_html = "";
            });            
        },
        error: function(response) {
          alert("Get all reviews for object error");
        }
    });
}

function get_overall_ratings(object_id) {
    $.ajax({
        url: "http://www.liffi.com/api/reviews/get_overall_rating/",
        type: "post",
        dataType: "json",
        data: {"object_id": object_id},
        success: function(response) {
            if (response.total_reviews > 0 ) {
                //alert("total: " + response.total_reviews);
                $("#total_reviews_count_span").html(response.total_reviews);
                //$("#all_reviews_count_span").html(response.total_reviews);
                $("#overall_ratings_span").html(response.overall_rating);
                var context_wise_ratings_html = "";
                $.each(response.context, function(index, value) {
                    context_wise_ratings_html += "<label><span>"+index+":&nbsp;</span><span>"+value+"</span></label><br>";
                });
                $("#context_wise_ratings").html(context_wise_ratings_html);
            }
        },
        error: function(response) {
            alert("Get overall ratings error");            
        }
    });  
}

function get_reviews_context(object_type) {
  $.ajax({
        url: "http://www.liffi.com/api/reviews/get_review_contexts/",
        type: "post",
        dataType: "json",
        data: {"object_type": object_type},
        success: function(response) {
          //console.log('get_review_contexts response: ' + response);
          var review_contexts_div_html = "";
          $.each(response, function(index, value) {
            //console.log('index: ' + index + ' | value: ' + value.context);
            if ( ! isNaN(index)) {
              var max_rating = 5, i=1;
              review_contexts_div_html += "<label>" + value.context+ "</label><select name='review_info[" + index + "][rating]'>";
              for(i=1; i<=5; i++) {
                review_contexts_div_html += "<option value='" + i + "'>" + i + "</option>";
              }
              review_contexts_div_html += "</select>";
              
              if (value.context.toLowerCase() == "overall") {
                review_contexts_div_html += "<input type='hidden' name='review_info[" + index + "][review_txt]' id='overall_review_txt' value='' />";
                overall_reviews_context_id_for_current_object = value.id;
              }              
              review_contexts_div_html += "<input type='hidden' name='review_info[" + index + "][review_context_id]' value='" + value.id + "' />";
              review_contexts_div_html += "<input type='hidden' name='review_info[" + index + "][object_id]' value='49' />";
            }            
          });
          $("#review_contexts_div").html(review_contexts_div_html);
        },
        error: function(response) {
            alert("Get reviews context error");            
        }
    });
}

function add_review() {
if ( ! confirm("Are you sure?")) {
  return false;
}
$("#overall_review_txt").val($("#review_txt").val());
var postData = $("#frm_add_review").serializeArray();
  $.ajax({
          url: "http://www.liffi.com/api/reviews/add/",
          type: "post",
          dataType: "json",
          data: postData,
          success: function(response) {
            console.log("Add review response: " + response);
            if (response.status == 1) {
              //alert("Thank you for your review. It will be published soon.")
              $("#frm_add_review").hide("slow");
              $("#add_review_form_div").addClass("basic-grey");
              $("#add_review_form_div").html("<center><h2>Thank you for your review. It will be published soon.</h2></center>");
              get_pending_reviews();
            }
          },
          error: function(response) {
              alert("Add review error");
          }
      });
}

function login() {
  console.log("Logging in amol@sp.com");
  $.ajax({
          url: "http://www.liffi.com/api/login/",
          type: "post",
          dataType: "json",
          data: {email: 'amol@sp.com', pass: 'ziffi'},
          success: function(response) {
            console.log("Login response: " + response);
          },
          error: function(response) {
              alert("Login Error");
          }
      });
}

function get_pending_reviews() {
  console.log("Retrieving Pending Reviews...");
  $("#pending_reviews_div").html("<center><h3>Loadin please wait...</h3><center>");
  $.ajax({
    url: 'http://www.liffi.com/api/reviews/get_pending_reviews/',
    type: 'post',
    dataType: 'json',
    success: function(response) {
      if (response.reviews == undefined) {
        $("#pending_reviews_div").html("<center><h3>No pending reviews found.</h3><center>");
        return false;
      }
      var pending_reviews_html = "";
      $("#pending_reviews_div").html("");
      
      $.each(response.reviews, function(review_id, review_details) {
          console.log("index: " + review_id + " | reviewer: " + review_details.reviewed_by_name);
          var review_txt = "";
          
          pending_reviews_html += "<br><div id='review_"+review_id+"' class='pending_review_details_div'>";
          if (review_details.reviewed_by !== undefined) {
            pending_reviews_html += "<span style='float:right;'><a href='javascript:void();' onclick='publish("+review_details.review_id+"," + review_details.reviewed_by + ");' class='publish_review_link'>Publish</a></span>";
            pending_reviews_html += "<span style='float:right;'><a href='javascript:void();' onclick='delete_review("+review_details.review_id+"," + review_details.reviewed_by + ");' class='publish_review_link'>Delete</a>&nbsp; / &nbsp;</span>";          
            pending_reviews_html += "<span><b>Ratings:</b> &nbsp;</span><span>4/5</span>&nbsp;<span>  |  <b>Booked With Ziffi?</b> &nbsp;</span><span>" + (review_details.appointment_id > 0 ? "Yes" : "No") + "</span><br><span id='reviewer_name'><em>"+review_details.reviewed_by_name+"</em></span>&nbsp; | &nbsp;<span id='reviewed_at'>" + review_details.reviews_created_at + "</span><br><br><div id='review_text_body'><span id='review_title_span_"+review_details.review_id+"'></span><div id='review_text_p_"+review_details.review_id+"' class='review_txt_div'><em>Review Body Text</em></div></div><br>";
          }
          
          $.each(review_details.review_info, function(review_context_link_id, review_context_rating_details){
              console.log("review_context_link_id: " + review_context_link_id + " | reviews_context_text: " + review_context_rating_details.reviews_context_text + " | review_txt: " + review_context_rating_details.review_txt);
              if (review_details.reviewed_by !== undefined) {
                pending_reviews_html += "<span><em>" + review_context_rating_details.reviews_context_text + "</em>(" + review_context_rating_details.rating + "/5)</span><br>";
              }
              if (review_context_rating_details.reviews_context_text != undefined && review_context_rating_details.reviews_context_text.toLowerCase() == 'overall') {
                review_txt = review_context_rating_details.review_txt;
                overall_reviews_context_link_id = review_context_rating_details.reviews_context_link_id;
              }
              if (review_context_rating_details.hasOwnProperty("replies")) {
                  pending_reviews_html += "<div id='replies' style='margin-left:2%; margin-bottom: 5px;'><br><em>Replies:</em><br>"
                  $.each(review_context_rating_details.replies, function(reply_reviews_context_link_id, reply_details) {
                      console.log("reply_reviews_context_link_id: " + reply_reviews_context_link_id + " | reviews_context_text: " + reply_details.reviews_context_text + " | review_txt: " + reply_details.review_txt);
                      pending_reviews_html += "<div id='reply_" + reply_reviews_context_link_id + "'>" + "<span style='margin-left:1%;'>" + reply_details.review_txt + "</span> - <span style='color:#0c65a5'>" + reply_details.reviews_context_link_created_by_name + "</span>&nbsp;&nbsp;<span style='color:#999'>" + reply_details.reviews_context_link_created_at + "</span><span style='float:right;'><a href='javascript:void();' onclick='publish("+reply_details.reviews_context_link_id+","+reviewer_id+",1);' style='color:white;'>Publish</a>&nbsp; / &nbsp;<a href='javascript:void();' onclick='delete_review("+reply_details.reviews_context_link_id+","+reviewer_id+",1);' style='color:white;'>Delete</a></span></div>";
                  });
                  pending_reviews_html += "</div>";    
              }
          });
          //pending_reviews_html += "<div id='voting_div_"+review_details.review_id+"' class='voting_div'><span id='upvote_count_span_"+overall_reviews_context_link_id+"' style='margin: 2px;'>&nbsp;"+review_details.upvotes+"&nbsp;</span><a href='javascript:void(0);'><img src='like-icon.png' alt='Upvote'></a> | <a href='javascript:void(0);'><img src='dislike-icon.png' alt='Downvote'></a><span id='downvote_count_span_"+overall_reviews_context_link_id+"' style='margin: 2px;'>&nbsp;"+review_details.downvotes+"&nbsp;</span> | <a href='javascript:void(0);'  alt='Report abuse' class='report_abuse_link'>Report Abuse</a></div>";
          pending_reviews_html += "</div>";
          $("#pending_reviews_div").append(pending_reviews_html);
          $("#review_title_span_"+review_details.review_id).html("<b>Overall</b>");
          $("#review_text_p_"+review_details.review_id).html("<em>" + review_txt + "</em>");
          pending_reviews_html = "";
      });      
    },
    error: function(response) {
      alert("Get pending reviews error.");
    }
  });
}

function publish(review_id, published_by, is_reply) {
  if (confirm("Are you sure?")) {
    console.log("publishing review: [" + review_id + "]");
    $.ajax({
            url: "http://www.liffi.com/api/reviews/publish/",
            type: "post",
            dataType: "json",
            data: {review_id: review_id, published_by: published_by, is_reply: is_reply},
            success: function(response) {
              if (response.status == 1 ) {
                alert("Review published: " + review_id);
                get_pending_reviews();
                get_all_reviews_for_object(object_id, object_type);
              } else {
                if (response.status_flag == 'ERROR_UNAUTHORIZED') {
                  alert("You are not authorized to perform this action.")
                } else {
                  alert("Please try again after some time.");
                }                
              }
            },
            error: function(response) {
                alert("Review Publish Error");
            }
        });
  }  
}

function delete_review(review_id, deleted_by, is_reply) {
  if (confirm("Are you sure?")) {
    console.log("Deleting review: [" + review_id + "]");
    var post_data = {"review_id":review_id, "deleted_by": deleted_by};
    if(is_reply) {
      post_data = {"review_context_link_id":review_id, "deleted_by": deleted_by};
    }
    $.ajax({
            url: "http://www.liffi.com/api/reviews/delete/",
            type: "post",
            dataType: "json",
            data: post_data,
            success: function(response) {
              console.log("Delete Reviews Response: " + response);              
              if (response.status == 1) {
                alert("Review deleted: " + review_id);
                if (is_reply) {
                  $("#reply_"+review_id).fadeOut("slow");
                }                
                //get_pending_reviews();
                //get_all_reviews_for_object(object_id, object_type);
              } else {
                if (response.status_flag == 'ERROR_UNAUTHORIZED') {
                  alert("You are not authorized to perform this action.")
                } else {
                  alert("Please try again after some time.");
                }
              }              
            },
            error: function(response) {
                alert("Review Delete Error");
            }
        });
  }  
}

function vote(review_id, vote_type, voted_by) {
  //alert("overall reviews context id: " + overall_reviews_context_id_for_current_object);
  console.log("Upvoting review: [" + review_id + "]");
  var post_data = {"review_id":review_id, "vote_type": vote_type, "voted_by": voted_by};
  $.ajax({
          url: "http://www.liffi.com/api/reviews/vote/",
          type: "post",
          dataType: "json",
          data: post_data,
          success: function(response) {
            console.log(vote_type + " review Response: " + response);              
            if (response.status == 1) {
              alert(vote_type + " successful: " + review_id);
              $("#"+vote_type+"_count_span_"+review_id).html(response.data.new_vote_count);
            } else {
              if (response.status_flag == 'ERROR_ALREADY_VOTED') {
                alert("You have already casted the same vote before.");
              } else {
                alert("Please try again after some time.");
              }
              //alert(vote_type + " failed.");
            }
          },
          error: function(response) {
              alert(vote_type + " Review Error");
          }
      });
}

function show_reply_form(id) {
  $("#reply_div_"+id).fadeIn("slow");
}
function reply(parent_id) {
  if ( ! confirm("Are you sure?")) {
    return false;
  }
  var postData = $("#add_reply_form_"+parent_id).serializeArray();
  $.ajax({
          url: "http://www.liffi.com/api/reviews/add/",
          type: "post",
          dataType: "json",
          data: postData,
          success: function(response) {
            console.log("Add reply response: " + response);
            if (response.status == 1) {
              $("#reply_div_"+parent_id).fadeOut("slow");
              alert("Thank you for your review. It will be published soon.");
              get_pending_reviews();
            } else {
              alert("Plese try again after some time.");
            }
          },
          error: function(response) {
              alert("Add review error");
          }
      });
}
</script>
</html>