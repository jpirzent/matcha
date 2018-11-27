var express = require('express');
var router = express.Router();
var session = require('express-session');
var mysql = require('mysql');
var rn = require('random-number');
var crypto = require('crypto');
var nodemailer = require('nodemailer');


var cr_key = crypto.createCipher('aes-128-cbc', 'P3n1s');
var cr_pwd = crypto.createCipher('aes-128-cbc', 'P@ssW0rd');


var conn = mysql.createConnection({
	host: 'localhost',
	user: 'matcha',
	password: '012345',
	database: 'matcha'
});

var trans = nodemailer.createTransport({
	service: 'gmail',
	auth: {
		user: 'wtc.test.jpirzent@gmail.com',
		pass: 'wethinkcode'
	}
});


/* Render index */
router.get('/', function(req, res, next) {
	/* req.session.u_id = 1; */
	res.render('index', { errors: req.session.errors });
	req.session.errors = null;
});

/* Render index */
router.get('/index', function(req, res, next) {
	/* req.session.u_id = 1; */
	res.render('index', { errors: req.session.errors });
	req.session.errors = null;
});

/* Render user_home */
router.get('/user_home', function(req, res, next) {
	/* req.session.u_id = 1; */
	res.render('user_home', { session: req.session });
});

/* Render signup */
router.get('/signup', function(req, res, next) {
	res.render('sign_up', {success: req.session.success, errors: req.session.errors});
	req.session.errors = null;
	req.session.success = null;
});


/* try signup */
router.post('/sign-in', function(req, res, next) {
	req.check('email', 'Email is Invalid').isEmail();
	req.check('first', 'First Name is Invalid').exists({checkFalsy: true});
	req.check('last', 'Last Name is Invalid').exists({checkFalsy: true});
	req.check('uid', 'Username is Invalid').exists({checkFalsy: true});
	req.check('first', 'First Name is Invalid').isString();
	req.check('last', 'Last Name is Invalid').isString();
	req.check('uid', 'Username is Invalid').isString();
	req.check('pwd', 'Password is invalid').isLength({min: 6});
	req.check('pwd', 'Password requires: 1 Capital, 1 Special, 1 Lowercase, and 1 Numeric Char. Also needs to be at least 6 chars long').matches(/^(?=.*\d)(?=.*[^a-zA-Z\d])(?=.*[a-z])(?=.*[A-Z]).{6,}$/);
	req.check('cpwd', 'Passwords Do not Match').equals(req.body.pwd);

	var err = req.validationErrors();

	if (err)
	{
		req.session.errors = err;
		req.session.success = false;
	}
	else
	{
		req.session.success = true;
		conn.connect(function(err){
			if (err)
			{
				throw err;
			}
			else
			{
				var first = req.body.first;
				var last = req.body.last;
				var email = req.body.email;
				var uid = req.body.uid;
				var pwd = cr_pwd.update(req.body.pwd, 'utf8', 'hex');
				pwd += cr_pwd.final('hex');
				var key = cr_key.update((rn({min: 1000, max: 9999, integer: true}) + uid), 'utf8', 'hex');
				key += cr_key.final('hex');

				var sql = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd, user_key) VALUES (" + conn.escape(first) + ", " + conn.escape(last) + ", "  + conn.escape(email) + ", " + conn.escape(uid) + ", " + conn.escape(pwd) + ", " + conn.escape(key) + ")";
				conn.query(sql, function(err, result){
					if (err)
					{
						throw err;
					}
				});
				var mailoptions = {
					from: 'matcha@gmail.com',
					to: email,
					subject: 'Verify Account: ' + uid,
					html: '<h1>Matcha</h1><p>Please follow the link below to verify your account</p><br><a href="http://localhost:8000/verify?key=' + key + '">Click Me!</a>'
				}
				trans.sendMail(mailoptions, function(error, info){
					if (error)
					{
						throw error;
					}
				});
			}
		});
	}
	res.redirect('/signup');
});

/* try login */
router.post('/login', function(req, res, next) {
	req.check('uid', 'Username is Required').exists({checkFalsy: true});
	req.check('pwd', 'Password is Required').exists({checkFalsy: true});
	
	var err = req.validationErrors();
	if (err)
	{
		req.session.errors = err;
		req.session.success = false;
		console.log(req.session.errors[0]);
	}
	else
	{
		var uid = req.body.uid;
		conn.connect(function(err) {
			var sql = "SELECT * FROM users WHERE user_uid = '" + uid + "'";
			conn.query(sql, function(err, result) {
				if (err)
				{
					throw err;
				}
				else
				{
					if (result[0].user_uid == uid)
					{
						
					}
					else
					{
						req.session.errors[2] = {location: 'body', param: 'uid', msg: 'Username is Required', value: ''};
					}
				}
			});
		});
	}

	res.redirect('/index');
});

/*try verify*/
router.get('/verify', function(req, res, next) {
	var key = req.query.key;

	conn.connect(function(err) {
		var sql = "SELECT * FROM users WHERE user_key = '" + key + "' LIMIT 1";
		conn.query(sql, function(err, result) {
			if (err)
			{
				throw err;
			}
			else
			{
				var sql = "UPDATE users SET user_verified = '1' WHERE user_key = '" + result[0].user_key + "' LIMIT 1";
				conn.query(sql, function(err, result) {
					if (err)
					{
						throw err;
					}
				});
			}
		});
	});
	res.redirect('/');
});

module.exports = router;
