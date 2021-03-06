"use strict";

var helpers = require("../../helpers/helpers");

exports["Asia/Pyongyang"] = {
	"1904" : helpers.makeTestYear("Asia/Pyongyang", [
		["1904-11-30T15:29:59+00:00", "23:59:59", "KST", -510],
		["1904-11-30T15:30:00+00:00", "00:30:00", "JCST", -540]
	]),

	"1927" : helpers.makeTestYear("Asia/Pyongyang", [
		["1927-12-31T14:59:59+00:00", "23:59:59", "JCST", -540],
		["1927-12-31T15:00:00+00:00", "23:30:00", "KST", -510]
	]),

	"1931" : helpers.makeTestYear("Asia/Pyongyang", [
		["1931-12-31T15:29:59+00:00", "23:59:59", "KST", -510],
		["1931-12-31T15:30:00+00:00", "00:30:00", "JCST", -540]
	]),

	"1937" : helpers.makeTestYear("Asia/Pyongyang", [
		["1937-09-30T14:59:59+00:00", "23:59:59", "JCST", -540],
		["1937-09-30T15:00:00+00:00", "00:00:00", "JST", -540]
	]),

	"1945" : helpers.makeTestYear("Asia/Pyongyang", [
		["1945-08-23T14:59:59+00:00", "23:59:59", "JST", -540],
		["1945-08-23T15:00:00+00:00", "00:00:00", "KST", -540]
	]),

	"1954" : helpers.makeTestYear("Asia/Pyongyang", [
		["1954-03-20T14:59:59+00:00", "23:59:59", "KST", -540],
		["1954-03-20T15:00:00+00:00", "23:00:00", "KST", -480]
	]),

	"1961" : helpers.makeTestYear("Asia/Pyongyang", [
		["1961-08-09T15:59:59+00:00", "23:59:59", "KST", -480],
		["1961-08-09T16:00:00+00:00", "01:00:00", "KST", -540]
	])
};