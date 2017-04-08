
<div class="container" ng-controller="BulletinBoardCtrl" ng-cloak>
	
	<br/><br/>
	<div class="row" id="bulletin" data-psychicid="<?= $current_login->id; ?>" ng-init="psychic[0].profile_img ? psychic[0].profile_img : ''">
		<div class="col-md-3 text-center">
			<div ng-if="psychic[0].profile_img">
				<img ng-src="{{ psychic[0].profile_img }}" width="70%">
			</div>
			<div ng-if="!psychic[0].profile_img">
				<img src="public_html/images/profile-img.png" width="70%">
			</div>
		</div>
		<div class="col-md-9">
			<!-- <h4>Reader Name: <b>{{ psychic[0].fname }} {{ psychic[0].fname }} </b></h4> -->
			<h4> Reader Username: <b>{{ psychic[0].username }}</b></h4>
			<h4> Email Address: <b>{{ psychic[0].email_address }}</b></h4>
			<h4> Mobile Number: <b>{{ psychic[0].mobile_num }}</b></h4>
			<h4> Home Number: <b>{{ psychic[0].home_phone }}</b></h4>

		</div>
	</div>
	<br/>
	<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#all" aria-controls="all" role="tab" data-toggle="tab">All <span class="badge">{{messages.length}}</span> </a></li>
   <!--  <li role="presentation"><a href="#available" aria-controls="available" role="tab" data-toggle="tab">Available <span class="badge">{{messages.length}}</span> </a></li>
    <li role="presentation">
    	<a href="#pending" aria-controls="pending" role="tab" data-toggle="tab">Pending</a>
    </li> -->
    <li role="presentation"><a href="#responded" aria-controls="responded" role="tab" data-toggle="tab" ng-click="resolved_messages_fn()" >Responded
	<span class="badge">{{resolved_messages.length}}</span>
    </a></li>
  </ul>
	
  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="all">
    	<br/>
		<table class="table bulletin-table"> 
			<thead> 
				<tr>
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-envelope font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Message ID</b></i>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-list-alt font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Text Message</b></i>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-calendar font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Date Sent</b></i>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-dashboard font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Time</b></i>
						</div>
					</th>
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-flag font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Country Code</b></i>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-phone font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Cellphone Number</b></i>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-user font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Client</b></i>
						</div>
					</th>
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-list-alt font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Shortcode</b></i>
						</div>
					</th> 
				</tr>
			</thead> 
			<tbody> 
				<tr ng-repeat="message in messages |  orderObjectBy:'sent_at':true " ng-show="get_page($index)" id="msg-{{message.id}}" ng-class="{'active': message.isPending}"> 
					<td class="text-center" ng-bind="message.id"></td> 
					<td class="text-center" ng-bind="message.message"></td> 
					<td class="text-center" ng-bind="formatDate(message.sent_at) | date:'shortDate'">
					</td> 
					<td class="text-center" ng-bind="formatDate(message.sent_at)| date:'shortTime'">
					</td> 
					<td class="text-center" ng-bind="message.country"></td>
					<td class="text-center" ng-bind="encrypt_number(message.number)"></td> 
					<td class="text-center" ng-bind="psychics[message.responded_by].name"></td> 
					<td class="text-center" ng-bind="message.shortcode"></td> 
					<td class="text-center">
						<button class="btn btn-sm accept-btn accept-{{message.id}}" 
						 ng-class="{'btn-default': message.isPending, 'btn-success' : !message.isPending }"
						 ng-disabled="message.isPending" data-toggle="modal" 
						 ng-click="accept_message(message, psychic)">Accept</button>
					</td> 
				</tr> 
			</tbody> 
		</table>

		
		<nav>
		  <ul class="pager">
		    <li class="previous" ng-class="{'hide': currentPage == 1}">
		    	<a href="" ng-click="prev_page($event)">
		    		<span aria-hidden="true">&larr;</span> Previous
		    	</a>
		    </li>
		    <li class="next" ng-class="{'hide': totalPage == currentPage}">
		    	<a href="" ng-click="next_page($event)">Next<span aria-hidden="true">&rarr;</span></a>
		    </li>
		  </ul>
		</nav>

    </div>
    <div role="tabpanel" class="tab-pane" id="responded">
    	<table class="table table-striped bulletin-table"> 
			<thead> 
				<tr>
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-envelope font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Message ID</b></i>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-list-alt font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Text Message</b></i>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-calendar font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Date Sent</b></i>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-dashboard font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Time</b></i>
						</div>
					</th>
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-flag font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Country Code</b></i>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-phone font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Cellphone Number</b></i>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-user font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Client</b></i>
						</div>
					</th>
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-list-alt font-size-50" aria-hidden="true"></span>
							<br/>
							<i><b>Shortcode</b></i>
						</div>
					</th> 
				</tr>
			</thead>  
			<tbody> 
				<tr style="cursor: pointer;" ng-repeat="resolved_message in resolved_messages | orderBy:'-sent_at'" ng-show="get_page_resolve($index)" id="msg-{{resolved_message.id}}" ng-click="showRespondedMsgDetails(resolved_message)"> 
					<td class="text-center" ng-bind="resolved_message.id"></td> 
					<td class="text-center" ng-bind="resolved_message.message"></td> 
					<td class="text-center" ng-bind="formatDate(resolved_message.sent_at) | date:'shortDate'">
					</td> 
					<td class="text-center" ng-bind="formatDate(resolved_message.sent_at)| date:'shortTime'">
					</td> 
					<td class="text-center" ng-bind="resolved_message.country"></td>
					<td class="text-center" ng-bind="encrypt_number(resolved_message.number)"></td> 
					<td class="text-center" ng-bind="resolved_message.responded_by"></td> 
					<td class="text-center" ng-bind="resolved_message.shortcode"></td> 
				</tr> 
				<tr>
					<td colspan="9">
						<nav>
						  <ul class="pager">
						    <li class="previous" ng-class="{'hide': currentPageResolved == 1}">
						    	<a href="" ng-click="prev_page_resolved($event)">
						    		<span aria-hidden="true">&larr;</span> Previous
						    	</a>
						    </li>
						    <li class="next" ng-class="{'hide': totalPageResolved == currentPageResolved}">
						    	<a href="" ng-click="next_page_resolved($event)">Next <span aria-hidden="true">&rarr;</span></a>
						    </li>
						  </ul>
						</nav>
					</td>
				</tr>
			</tbody> 


		</table>


		
    </div>
  </div>


<div id="sms-answer" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"><b>SMS Answer</b></h4>
	      </div>
	       <div class="modal-body">
				<div class="alert alert-{{errType}}" role="alert" ng-show="hasErr" ng-repeat="error in errors">
					{{ error }}
				</div>
		        <h4 class="text-center"><strong>ANSWER BOARD</strong></h4>
		        <p><strong>ID Number: {{ message.id }} </strong></p>
		        <p><strong>Reader: {{ psychic[0].fname }} {{ psychic[0].lname }} </strong></p>
		        <p>From: {{encrypt_number(message.number)}}</p>
				<p>Shortcode: {{message.shortcode}}</p>
				<b>Message:</b>
				<p>{{message.message}}</p>
		        <p>Answer</p>
		        <textarea class="message" ng-model="message.replied_message"></textarea>
		        <div>
		        	<input type="checkbox" name="allow_button" id="allow_button" checked="checked"/> 
		        	<label for="allow_button">Hit 'Enter' to send message</label>
		        	<span class="char-count">0 (0)</span>
		        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary send-message" ng-click="send_message(message)">Submit Query</button>
	      </div>

	    </div>
	  </div>
	</div>

<div id="sms-details" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"><b>SMS</b></h4>
	      </div>
	       <div class="modal-body">
		        <h4 class="text-center"><strong>SMS DETAILS</strong></h4>
		        <p><strong>ID Number: {{ message.id }} </strong></p>
		        <p><strong>Reader: {{ psychic[0].fname }} {{ psychic[0].lname }} </strong></p>
		        <p>From: {{encrypt_number(message.number)}}</p>
				<p>Shortcode: {{message.shortcode}}</p>
				<b>Message:</b>
				<p>{{message.message}}</p>
		        <p>Answer</p>
		        {{ message.replied_message }}
		       <!--  <textarea class="message" ng-model="message.replied_message"></textarea>
		        <div>
		        	<input type="checkbox" name="allow_button" id="allow_button" checked="checked"/> 
		        	<label for="allow_button">Hit 'Enter' to send message</label>
		        	<span class="char-count">0 (0)</span>
		        </div> -->
	      </div>
	      <div class="modal-footer">
	       <!--  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <b utton type="button" class="btn btn-primary send-message" ng-click="send_message(message)">Submit Query</button>-->
	      </div>

	    </div>
	  </div>
	</div>


	<audio class="audio message_receive" type="audio/mpeg" src="public_html/assets/knock_brush.mp3"></audio>
</div>