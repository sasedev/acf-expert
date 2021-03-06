"use strict";

var tz = require("../../moment-timezone-utils").tz;

function compare(test, data) {
	var i,
		input,
		output,
		expected,
		precision;

	for (i = 0; i < data.length; i++) {
		input	 = data[i][0];
		precision = data[i][1];
		expected	= data[i][2];
		output	= tz.packBase60(input, precision);
		test.equal(output, expected, 'packing ' + input + ' to ' + precision + ' decimal places should equal ' + expected);
	}

	test.done();
}

exports['pack-base-60'] = {
	ints : function (test) {
		compare(test, [
			[0,	0, '0'],
			[1,	0, '1'],
			[9,	0, '9'],
			[10, 0, 'a'],
			[35, 0, 'z'],
			[36, 0, 'A'],
			[59, 0, 'X'],
			[60, 0, '10'],
			[60 +	1, 0, '11'],
			[60 + 10, 0, '1a'],
			[60 + 35, 0, '1z'],
			[60 + 36, 0, '1A'],
			[60 + 59, 0, '1X'],
			[60 * 60, 0, '100'],
			[2799360000000, 0, '10000000']
		]);
	},

	floats : function (test) {
		compare(test, [
			[0, 10, '0'],
			[ 1 / 60, 1, '.1'],
			[ 9 / 60, 1, '.9'],
			[10 / 60, 1, '.a'],
			[35 / 60, 1, '.z'],
			[36 / 60, 1, '.A'],
			[59 / 60, 1, '.X'],
			[ 1 / 60, 2, '.1'],
			[59 / 60, 2, '.X'],
			[59 / 60, 6, '.X'],
			[1 / 3600, 1, '0'],
			[1 / 3600, 2, '.01'],
			[(1 / 60) + ( 1 / 3600), 2, '.11'],
			[(1 / 60) + (10 / 3600), 2, '.1a'],
			[(1 / 60) + (35 / 3600), 2, '.1z'],
			[(1 / 60) + (36 / 3600), 2, '.1A'],
			[(1 / 60) + (59 / 3600), 2, '.1X'],
			[(1 / 60) + ( 1 / 3600), 3, '.11'],
			[(1 / 60) + (59 / 3600), 3, '.1X'],
			[(1 / 60) + (59 / 3600), 8, '.1X'],
			[1 / (60 * 60 * 60 * 60 * 60 * 60 * 60 * 60), 8, '.00000001']
		]);
	},

	negative : function (test) {
		compare(test, [
			[-0,	0, '0'],
			[-1,	0, '-1'],
			[-60, 0, '-10'],
			[-(60 + 59), 0, '-1X'],
			[ -1 / 60, 1, '-.1'],
			[-35 / 60, 1, '-.z'],
			[ -1 / 3600, 1, '0'],
			[-((1 / 60) + (59 / 3600)), 8, '-.1X'],
			[-1 / (60 * 60 * 60 * 60 * 60 * 60 * 60 * 60), 8, '-.00000001']
		]);
	},
};
