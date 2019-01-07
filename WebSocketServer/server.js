/*jshint esversion: 6 */

var app = require('http').createServer();

// Se tiverem problemas com "same-origin policy" deverão activar o CORS.

// Aqui, temos um exemplo de código que ativa o CORS (alterar o url base)

// var app = require('http').createServer(function(req,res){
// Set CORS headers
//  res.setHeader('Access-Control-Allow-Origin', 'http://---your-base-url---');
//  res.setHeader('Access-Control-Request-Method', '*');
//  res.setHeader('Access-Control-Allow-Methods', 'UPGRADE, OPTIONS, GET');
//  res.setHeader('Access-Control-Allow-Credentials', true);
//  res.setHeader('Access-Control-Allow-Headers', req.header.origin);
//  if ( req.method === 'OPTIONS' || req.method === 'UPGRADE' ) {
//      res.writeHead(200);
//      res.end();
//      return;
//  }
// });

// NOTA: A solução correta depende da configuração do próprio servidor,
// e alguns casos do próprio browser.
// Assim sendo, não se garante que a solução anterior funcione.
// Caso não funcione é necessário procurar/investigar soluções alternativas

var io = require('socket.io')(app);

var LoggedUsers = require('./loggedusers.js');

app.listen(8080, function(){
    console.log('listening on *:8080');
});

// ------------------------
// Estrutura dados - server
// ------------------------

// loggedUsers = the list (map) of logged users.
// Each list element has the information about the user and the socket id
// Check loggedusers.js file

let loggedUsers = new LoggedUsers();

io.on('connection', function (socket) {
    console.log('client has connected (socket ID = '+socket.id+')' );

    socket.on('order_changed', function (changedOrder) {
      socket.broadcast.emit('order_changed', changedOrder);
    });

    // socket.broadcast.emit emit para todos os sockets menos o que enviou (socket.io cheetsheat)
    // socket.on('msg_from_client', msg=>{
    //   io.sockets.emit('msg_from_server', msg);
    // });
    //
    // socket.on('msg_from_client_dep', (msg, user)=>{
    //   if(user){
    //     io.to('department_'+user.department_id).emit('msg_from_server_dep', msg);
    //   }
    // });
    //
    socket.on('privateMessage',  (msg, sourceUser, destUser)=> {
		    let userInfo = loggedUsers.userInfoByID(destUser.id);
		      let socket_id = userInfo !== undefined ? userInfo.socketID : null;
		        if (socket_id === null) {
			           socket.emit('privateMessage_unavailable', destUser);
		        } else {
			              io.to(socket_id).emit('privateMessage', msg, sourceUser);
			              socket.emit('privateMessage_sent', msg, destUser);
		          }
    });


    // socket.on('private_message', (msg, to, from)=>{
    //   const userInfo = loggedUsers.userInfoBy(to.id);
    //   if(userInfo){
    //     io.to(userInfo.socketID).emit('private_message_server', msg, from);
    //   }else{
    //
    //   }
    //
    // });
    //
    // socket.on('user_enter', user=>{
    //   if(user){
    //     socket.join('department_'+user.department_id);
    //     loggedUsers.addUsersInfo(user, socket.id);
    //   }
    // });


    socket.on('user_enter', function (user) {
        if (user !== undefined && user !== null)
        {
            socket.join('notifications');
            loggedUsers.addUserInfo(user, socket.id);
            console.log('User ' + user.name + ' has join notifications' );
        }

    });

    socket.on('user_exit', function (user) {
        if (user !== undefined && user !== null)
        {
            socket.leave('notifications');
            loggedUsers.removeUserInfoByID(user.id);
            console.log('User ' + user.name +' has left notifications'  );
        }

    });
});
