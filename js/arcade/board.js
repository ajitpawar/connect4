// if (!Array.prototype.last){
//     Array.prototype.last = function(){
//         return this[this.length - 1];
//     };
// };

$(function(){

	var nextPlayerColor = $('#next-player');
	var nextColor;
	var stateChanged;
	var gameover = false;

	function updateMatchStateDB(matchState){
		var data = {matchStatus: JSON.stringify(matchState)};
		var urlUMS = baseURL+"board/updateMatchState";
		$.ajax({
			type: "POST",
			url: urlUMS,
			data: {matchState: JSON.stringify(matchState)},
			success: function(data) {
				return data["matchState"];
			},
			error: function(jqXHR, textStatus, errorThrown){
					console.log(jqXHR);
					console.log(textStatus);
					console.log(errorThrown);
			}
		});
	}

	var winner = "";

	function checkForVictor(){
		//Check Rows First
		var board = matchState['board'];
		var winningPositions = [];
		var victory = false;

		function checkRow(){
			console.log("Checking for row victors");
			winner = "";
			winningPositions.length = 0;
			for(var i = 0; i<6; i++){//Row
				winner = "";
				winningPositions.length = 0;
				for(var j = 0; j<7; j++){//Columns
					if(board[3][i] == "none"){
						break;
					}
					if(board[j][i] != "none" && board[j+1] != undefined){
						if( board[j][i] == board[j+1][i] ){
							winner = board[j][i];
							winningPositions.push( "("+j+","+i+")" );
							if( winningPositions.length == 3 ){
								winningPositions.push( "("+(j+1)+","+(i)+")" );
							}
						}
						else{
							winner = "";
							winningPositions.length = 0;
						}
						if( winningPositions.length == 4 ){
							console.log(winner+" wins!");
							console.log("ROW: "+winningPositions);
							return winner;
						}
					}
				}
			}
			return false;
		}

		function checkColumn(){
			console.log("Checking for row victors");
			winningPositions.length = 0;
			winner = "";
			for(var j = 0; j<7; j++){//Columns
				for(var i = 0; i<6; i++){//Row
					if(board[j][2] != board[j][3]){
						break;
					}
					if(board[j][i] == "none"){
						continue;
					}
					if(board[j][i+1] == undefined){

					}
					else {
						if( board[j][i] == board[j][i+1] ){
							winner = board[j][i];
							winningPositions.push( "("+j+","+i+")" );
							// because we are matching couples of cells each time, when the length of 'winningPositions' is 3, it means [a=b,b=c && c=d] so we need to manually store the last position
							if( winningPositions.length == 3 ){
								winningPositions.push( "("+(j)+","+(i+1)+")" );
							}
						}
						else{
							winningPositions.length = 0;
							winner = "";
						}
						// if [a=b,b=c && c=d] then the array will now have 4 itmes (positions) for 4 cells of the same kind
						if( winningPositions.length == 4 ){
							console.log(winner+" wins!");
							console.log("COLUMNS: "+winningPositions);
							return winner;
						}
					}
				}
			}
			return false;
		}

		function checkDiagonal(){
			console.log("Checking for diagonal victors");
			winningPositions.length = 0;
			winner = "";
			function checkPair(j,i,delta){
				winningPositions.push("("+j+","+i+")");
				winner = board[j][i];
				if(board[j+delta] == undefined){

				}
				else {
					if(board[j+delta][i-1] && board[j][i] == board[j+delta][i-1]){
						checkPair(j+delta,i-1,delta);
					}
				}
				if(winningPositions.length == 4){
					return true;
				}
			}
			for(var j = 0; j<6; j++){//Columns
				for(var i = 0; i<6; i++){//Row
					winningPositions.length = 0;
					winner = "";
					if(board[j+1][i-1] && board[j+1][i-1] == "none"){
						continue;
					}
					else {
						if(checkPair(j,i,-1)){
							console.log(winner+" wins!");
							console.log("DIAGONAL"+winningPositions);
							return winner;
						}
					}
				}
			}

			for(var j = 0; j<6; j++){//Columns
				for(var i = 5; i>=0; i--){//Row
					winningPositions.length = 0;
					if(board[j+1][i-1] && board[j+1][i-1] == "none"){
						continue;
					}
					else {
						if(checkPair(j,i,1)){
							console.log("DIAGONAL"+winningPositions);
							console.log(winner+" wins!");
							return winner;
						}
					}
				}
			}
			return false;
		}

		return checkRow() || checkColumn() || checkDiagonal() ;
	}

	function updateBoardSlots(){
		var color;
		var div;
		for (var i = 0; i < 7; i++) {
			for(var j = 0; j < 6; j++){
				color = matchState["board"][i][j];
				div = $($('aside')[i]).find('div:nth-child('+(j+1)+')');
				div.removeClass().addClass(matchState["board"][i][j]);
				if(color == 'none'){
					div.removeClass();
				}
				else {
					div.addClass('chip');

				}
			}
		}
	}


	function getMatchState(){
		$.getJSON(baseURL+"board/getMatchState", function (data,text,jqXHR){
			matchState = data;
			nextColor = matchState["status"];
			nextPlayerColor.removeClass().addClass(matchState["status"]);
			var yourColor;
			if(matchState["host"] == user){
				yourColor = "blue";
			}
			else {
				yourColor = "red";
			}
			if(user == matchState["host"] && matchState["status"] == "blue"){
				$('#game').removeClass().addClass('your-turn');
			}
			else if(user == matchState["invitee"] && matchState["status"] == "red"){
				$('#game').removeClass().addClass('your-turn');
			}
			else {
				$('#game').removeClass().addClass('their-turn');
			}
			updateBoardSlots();
			if(matchState['state'] == "tie"){
				gameover = true;
				alert("There has been a tie!");
				setTimeout(function(){window.location.href = baseURL+'arcade/index';},2000);
			}
			else if(matchState['state'] == "blue"){
				gameover = true;
				alert("Blue has won!");
				setTimeout(function(){window.location.href = baseURL+'arcade/index';},2000);
			}
			else if (matchState['state'] == "red"){
				gameover = true;
				alert("Red has won!");
				setTimeout(function(){window.location.href = baseURL+'arcade/index';},2000);
			}
		});
	}


	if(status == 'waiting') {
		var matchState = {};
		var board_array = new Array(7);
		for (var i = 0; i < 7; i++) {
			board_array[i] = new Array(6);
		}
		for (var i = 0; i < 7; i++) {
			for(var j = 0; j < 6; j++){
				board_array[i][j] = "none"
			}
		}
		matchState["board"] = board_array;
		matchState["host"] = user;
		matchState["invitee"] = otherUser;
		matchState["hostcolor"] = "blue";
		matchState["inviteecolor"] = "red";
		matchState["status"] = "waiting"; //blue red
		matchState["state"] = "active";
	}
	else{
		getMatchState();
	}

	$('.their-turn').on('click', 'div', function(){alert("Not your turn")});

	$('body').everyTime(2000,function(){
		if (status == 'waiting') {
			$.getJSON(baseURL+'arcade/checkInvitation',function(data, text, jqZHR){
				if (data && data.status=='rejected') {
					alert("Sorry, your invitation to play was declined!");
					window.location.href = baseURL+'arcade/index';
				}
				if (data && data.status=='accepted') {
					matchState['status'] = "blue";
					updateMatchStateDB(matchState);
					getMatchState();
					status = 'playing';
					$('#status').html('Playing ' + otherUser);
				}	
			});
		}
		else {
			if($('#game').hasClass('their-turn') && !gameover){
				getMatchState();
			}
		}
		$.getJSON(baseURL+"board/getMsg", function (data,text,jqXHR){
			if (data && data.status=='success') {
				var conversation = $('[name=conversation]').val();
				var msg = data.message;
				if (msg){
					conversation += "\n" + otherUser + ": " + msg;
					$('[name=conversation]').val(conversation);
				}
			}
		});
	});

	$('form').submit(function(){
		var arguments = $(this).serialize();
		var url = baseURL+"board/postMsg";
		$.post(url,arguments, function (data,textStatus,jqXHR){
			var conversation = $('[name=conversation]').val();
			var msg = $('[name=msg]').val();
			$('[name=conversation]').val(conversation + "\n" + user + ": " + msg);
		});
		return false;
	});	

	function placeChip($this){
		var parent = $this.parent();
		var x_0 = $('aside').index(parent);
		var y_0 = parent.find('div').index($this);
		var place_tmp = $(parent.find('div:not(.chip)'));
		if(place_tmp.length == 0){
			return false;
		}
		else {
			var place = $(place_tmp[place_tmp.length-1]);
			var index = $(matchState["board"][x_0]).index(place);
			matchState["board"][x_0][place_tmp.length-1] = nextColor;
			place.addClass(nextColor).addClass("chip");
			$('#game').removeClass().addClass('their-turn');
			return true;
		}
		return true;
	}

	function changePlayer(color){
		matchState["status"] = color;
		nextPlayerColor.removeClass().addClass(color+"-next");
	}

	function onClickDiv(){
		//Take event off to prevent multiple clicks
		$('#game-container').off('click', '.your-turn div', onClickDiv);
		if(placeChip($(this))){
			var victor = checkForVictor();
			if(victor == "red" || victor == "blue" || victor == "tie"){
				//alert(victor.charAt(0).toUpperCase() + victor.slice(1)+" has won!");
				gameover = true;
				matchState['state'] = victor;
				matchState = updateMatchStateDB(matchState);
				setTimeout(function(){
					window.location.href = baseURL+'arcade/index';
				},2000);
			}
			if(nextColor === "blue"){
				nextColor = "red";
			}
			else {
				nextColor = "blue";
			}
			changePlayer(nextColor);
			matchState = updateMatchStateDB(matchState);
		}
		$('#game-container').on('click', '.your-turn div', onClickDiv);
	}

	$('#game-container').on('click', '.your-turn div', onClickDiv);


	$('#get-match-state').on('click', function(){
		console.log("Getting match state");
		$.getJSON(baseURL+"board/getMatchState", function (data,text,jqXHR){
			$('#php-receive').text(JSON.stringify(data));
		});
	});

});