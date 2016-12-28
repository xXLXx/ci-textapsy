
<div class="container" ng-controller="BulletinBoardCtrl" ng-cloak>
	
	<div class="row">
		<div class="pull-right"> 
			Available Messages&nbsp; 
			<span class="badge">14</span>
		</div>	
	</div>
	<br/><br/>
	<div class="row">
		<div class="col-md-12" role="main">
		
		</div>
	</div>
	

	<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#all" aria-controls="all" role="tab" data-toggle="tab">All <span class="badge">{{messages.length}}</span> </a></li>
    <li role="presentation"><a href="#available" aria-controls="available" role="tab" data-toggle="tab">Available <span class="badge">{{messages.length}}</span> </a></li>
    <li role="presentation">
    	<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Pending</a>
    </li>
    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
  </ul>
	
  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="all">
		<table class="table"> 
			<thead> 
				<tr>
					<th></th>
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-envelope font-size-50" aria-hidden="true"></span>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-list-alt font-size-50" aria-hidden="true"></span>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-calendar font-size-50" aria-hidden="true"></span>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-dashboard font-size-50" aria-hidden="true"></span>
						</div>
					</th>
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-flag font-size-50" aria-hidden="true"></span>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-phone font-size-50" aria-hidden="true"></span>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-user font-size-50" aria-hidden="true"></span>
						</div>
					</th>
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-list-alt font-size-50" aria-hidden="true"></span>
						</div>
					</th> 
				</tr>
			</thead> 
			<tbody> 
				<tr class="" ng-repeat="message in messages | orderBy:'-sent_at'" ng-show="get_page($index)" id="msg-{{message.id}}"> 
					<td class="text-center"><span class="badge-status-red"></span></td>
					<td class="text-center" ng-bind="message.id"></td> 
					<td class="text-center" ng-bind="message.message"></td> 
					<td class="text-center" ng-bind="formatDate(message.sent_at) | date:'shortDate'">
					</td> 
					<td class="text-center" ng-bind="formatDate(message.sent_at)| date:'shortTime'">
					</td> 
					<td class="text-center" ng-bind="message.country"></td>
					<td class="text-center" ng-bind="message.number"></td> 
					<td class="text-center" ng-bind="message.responded_by"></td> 
					<td class="text-center" ng-bind="message.shortcode"></td> 
					<td class="text-center"><button class="btn btn-success btn-sm" 
					data-toggle="modal" ng-click="accept_message(message)">Accept</button></td> 
				</tr> 
			</tbody> 
		</table>
    </div>
    <div role="tabpanel" class="tab-pane" id="profile">
    	<table class="table"> 
			<thead> 
				<tr>
					<th></th>
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-envelope font-size-50" aria-hidden="true"></span>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-list-alt font-size-50" aria-hidden="true"></span>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-calendar font-size-50" aria-hidden="true"></span>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-dashboard font-size-50" aria-hidden="true"></span>
						</div>
					</th>
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-flag font-size-50" aria-hidden="true"></span>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-phone font-size-50" aria-hidden="true"></span>
						</div>
					</th> 
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-user font-size-50" aria-hidden="true"></span>
						</div>
					</th>
					<th>
						<div class="text-center">
							<span class="glyphicon glyphicon-list-alt font-size-50" aria-hidden="true"></span>
						</div>
					</th> 
				</tr>
			</thead> 
			<tbody> 
				<tr class="" ng-repeat="message in messages | orderBy:'-sent_at'" ng-show="get_page($index)" id="msg-{{message.id}}"> 
					<td class="text-center"><span class="badge-status-red"></span></td>
					<td class="text-center" ng-bind="message.id"></td> 
					<td class="text-center" ng-bind="message.message"></td> 
					<td class="text-center" ng-bind="formatDate(message.sent_at) | date:'shortDate'">
					</td> 
					<td class="text-center" ng-bind="formatDate(message.sent_at)| date:'shortTime'">
					</td> 
					<td class="text-center" ng-bind="message.country"></td>
					<td class="text-center" ng-bind="message.number"></td> 
					<td class="text-center" ng-bind="message.responded_by"></td> 
					<td class="text-center" ng-bind="message.shortcode"></td> 
					<td class="text-center"><button class="btn btn-success btn-sm" 
					data-toggle="modal" ng-click="accept_message(message)">Accept</button></td> 
				</tr> 
			</tbody> 
		</table>
    </div>
    <div role="tabpanel" class="tab-pane" id="messages">...</div>
    <div role="tabpanel" class="tab-pane" id="settings">...</div>
  </div>


	<div id="sms-answer" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="decline_message(message)"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"><b>SMS Answer</b></h4>
	      </div>
	       <div class="modal-body">
		        <h4 class="text-center"><strong>ANSWER BOARD</strong></h4>
		        <p>From: {{message.number}}</p>
				<p>Shortcode: {{message.shortcode}}</p>
				<b>Message:</b>
				<p>{{message.message}}</p>
		        <p>Answer</p>
		        <textarea class="message" ng-model="message.replied_message"></textarea>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" ng-click="decline_message(message)" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary send-message" ng-click="send_message(message)">Submit Query</button>
	      </div>

	    </div>
	  </div>
	</div>

	<nav>
	  <ul class="pager">
	    <li class="previous" ng-class="{'hide': currentPage == 1}">
	    	<a href="" ng-click="prev_page($event)">
	    		<span aria-hidden="true">&larr;</span> Previous
	    	</a>
	    </li>
	    <li class="next" ng-class="{'hide': currentPage == totalPage}">
	    	<a href="" ng-click="next_page($event)">Next <span aria-hidden="true">&rarr;</span></a>
	    </li>
	  </ul>
	</nav>
</div>

<div>

  



<script>
  var socket = io('http://localhost:3000');
  socket.on('message_recieved', function (data) {
    console.log(data);
  });
</script>