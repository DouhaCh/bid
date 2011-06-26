APE.Core = new Class({

	Extends: APE.Core,

	initialize: function(options) {
		this.options.debugOutput = console.log;
		this.onRaw('debug', function(param) {
			for (var i = 0; i < param.data.msg.length; i++) {
				this.options.debugOutput('[Server]Ape.log:', param.data.msg[i]);
			}
		});

		this.addEvent('onCmd', function(cmd, args) {
			this.options.debugOutput('[Sending] {' + cmd + '}', args);
		});
		this.addEvent('onRaw', function(args) {
			if (args.raw != 'debug' && args.raw != 'CLOSE') this.options.debugOutput('[Receiving] {' + args.raw + '}', args);
		});

		this.parent(options);
	},

	connect: function(args, options) {
		if (!args) args = {};
		args.sendDebug = true;
		this.parent(args, options);
	},

});
