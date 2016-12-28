
<div class="container" ng-controller="BulletinBoardCtrl" ng-cloak>
	
	<br/><br/>
	<div class="row" id="bulletin" data-psychicid="<?= $current_login->id; ?>">
		<div class="col-md-12" role="main">
			Name: <b>{{ psychic[0].fname }} {{ psychic[0].fname }} </b>
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
    <li role="presentation"><a href="#responded" aria-controls="responded" role="tab" data-toggle="tab"ng-click="resolved_messages_fn()" >Responded
	<span class="badge">{{resolved_messages.length}}</span>
    </a></li>
  </ul>
	
  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="all">
		<table class="table bulletin-table"> 
			<thead> 
				<tr>
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
				<tr ng-repeat="message in messages track by $index | orderBy:'-sent_at'" ng-show="get_page($index)" id="msg-{{message.id}}" ng-class="{'active': message.isPending}"> 
					<td class="text-center" ng-bind="message.id"></td> 
					<td class="text-center" ng-bind="message.message"></td> 
					<td class="text-center" ng-bind="formatDate(message.sent_at) | date:'shortDate'">
					</td> 
					<td class="text-center" ng-bind="formatDate(message.sent_at)| date:'shortTime'">
					</td> 
					<td class="text-center" ng-bind="message.country"></td>
					<td class="text-center" ng-bind="message.number"></td> 
					<td class="text-center" ng-bind="psychics[message.responded_by].name"></td> 
					<td class="text-center" ng-bind="message.shortcode"></td> 
					<td class="text-center">
						<button class="btn btn-sm accept-{{message.id}}" 
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
				</tr>
			</thead> 
			<tbody> 
				<tr class="" ng-repeat="resolved_message in resolved_messages | orderBy:'-sent_at'" ng-show="get_page_resolve($index)" id="msg-{{resolved_message.id}}"> 
					<td class="text-center"><span class="badge-status-green"></span></td>
					<td class="text-center" ng-bind="resolved_message.id"></td> 
					<td class="text-center" ng-bind="resolved_message.message"></td> 
					<td class="text-center" ng-bind="formatDate(resolved_message.sent_at) | date:'shortDate'">
					</td> 
					<td class="text-center" ng-bind="formatDate(resolved_message.sent_at)| date:'shortTime'">
					</td> 
					<td class="text-center" ng-bind="resolved_message.country"></td>
					<td class="text-center" ng-bind="resolved_message.number"></td> 
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
				<div class="alert alert-danger" role="alert" ng-show="hasErr" ng-repeat="error in errors">
					{{ error }}
				</div>
		        <h4 class="text-center"><strong>ANSWER BOARD</strong></h4>
		        <p><strong>ID Number: {{ message.id }} </strong></p>
		        <p><strong>Reader: {{ psychic[0].fname }} {{ psychic[0].lname }} </strong></p>
		        <p>From: {{message.number}}</p>
				<p>Shortcode: {{message.shortcode}}</p>
				<b>Message:</b>
				<p>{{message.message}}</p>
		        <p>Answer</p>
		        <textarea class="message" ng-model="message.replied_message"></textarea>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary send-message" ng-click="send_message(message)">Submit Query</button>
	      </div>

	    </div>
	  </div>
	</div>

</div>

<div>