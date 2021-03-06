// Generated by CoffeeScript 1.7.1
(function() {
	var deprecate, hasModule, isArray, makeTwix,
	__slice = [].slice;

	hasModule = (typeof module !== "undefined" && module !== null) && (module.exports != null);

	deprecate = function(name, instead, fn) {
	var alreadyDone;
	alreadyDone = false;
	return function() {
		var args;
		args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
		if (!alreadyDone) {
		if (typeof console !== "undefined" && console !== null) {
			if (typeof console.warn === "function") {
			console.warn("#" + name + " is deprecated. Use #" + instead + " instead.");
			}
		}
		}
		alreadyDone = true;
		return fn.apply(this, args);
	};
	};

	isArray = function(input) {
	return Object.prototype.toString.call(input) === '[object Array]';
	};

	makeTwix = function(moment) {
	var Twix, languagesLoaded;
	if (moment == null) {
		throw "Can't find moment";
	}
	languagesLoaded = false;
	Twix = (function() {
		function Twix(start, end, parseFormat, options) {
		var _ref;
		if (options == null) {
			options = {};
		}
		if (typeof parseFormat !== "string") {
			options = parseFormat != null ? parseFormat : {};
			parseFormat = null;
		}
		if (typeof options === "boolean") {
			options = {
			allDay: options
			};
		}
		this.start = moment(start, parseFormat, options.parseStrict);
		this.end = moment(end, parseFormat, options.parseStrict);
		this.allDay = (_ref = options.allDay) != null ? _ref : false;
		this._trueStart = this.allDay ? this.start.clone().startOf("day") : this.start;
		this._trueEnd = this.allDay ? this.end.startOf('d').clone().add(1, "day") : this.end;
		}

		Twix._extend = function() {
		var attr, first, other, others, _i, _len;
		first = arguments[0], others = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
		for (_i = 0, _len = others.length; _i < _len; _i++) {
			other = others[_i];
			for (attr in other) {
			if (typeof other[attr] !== "undefined") {
				first[attr] = other[attr];
			}
			}
		}
		return first;
		};

		Twix.defaults = {
		twentyFourHour: false,
		allDaySimple: {
			fn: function(options) {
			return function() {
				return options.allDay;
			};
			},
			slot: 0,
			pre: " "
		},
		dayOfWeek: {
			fn: function(options) {
			return function(date) {
				return date.format(options.weekdayFormat);
			};
			},
			slot: 1,
			pre: " "
		},
		allDayMonth: {
			fn: function(options) {
			return function(date) {
				return date.format("" + options.monthFormat + " " + options.dayFormat);
			};
			},
			slot: 2,
			pre: " "
		},
		month: {
			fn: function(options) {
			return function(date) {
				return date.format(options.monthFormat);
			};
			},
			slot: 2,
			pre: " "
		},
		date: {
			fn: function(options) {
			return function(date) {
				return date.format(options.dayFormat);
			};
			},
			slot: 3,
			pre: " "
		},
		year: {
			fn: function(options) {
			return function(date) {
				return date.format(options.yearFormat);
			};
			},
			slot: 4,
			pre: ", "
		},
		time: {
			fn: function(options) {
			return function(date) {
				var str;
				str = date.minutes() === 0 && options.implicitMinutes && !options.twentyFourHour ? date.format(options.hourFormat) : date.format("" + options.hourFormat + ":" + options.minuteFormat);
				if (!options.groupMeridiems && !options.twentyFourHour) {
				if (options.spaceBeforeMeridiem) {
					str += " ";
				}
				str += date.format(options.meridiemFormat);
				}
				return str;
			};
			},
			slot: 5,
			pre: ", "
		},
		meridiem: {
			fn: function(options) {
			return (function(_this) {
				return function(t) {
				return t.format(options.meridiemFormat);
				};
			})(this);
			},
			slot: 6,
			pre: function(options) {
			if (options.spaceBeforeMeridiem) {
				return " ";
			} else {
				return "";
			}
			}
		}
		};

		Twix.registerLang = function(name, options) {
		return moment.locale(name, {
			twix: Twix._extend({}, Twix.defaults, options)
		});
		};

		Twix.prototype.isSame = function(period) {
		return this.start.isSame(this.end, period);
		};

		Twix.prototype.length = function(period) {
		return this._trueEnd.diff(this._trueStart, period);
		};

		Twix.prototype.count = function(period) {
		var end, start;
		start = this.start.clone().startOf(period);
		end = this.end.clone().startOf(period);
		return end.diff(start, period) + 1;
		};

		Twix.prototype.countInner = function(period) {
		var end, start, _ref;
		_ref = this._inner(period), start = _ref[0], end = _ref[1];
		if (start >= end) {
			return 0;
		}
		return end.diff(start, period);
		};

		Twix.prototype.iterate = function(intervalAmount, period, minHours) {
		var end, hasNext, start, _ref;
		if (intervalAmount == null) {
			intervalAmount = 1;
		}
		_ref = this._prepIterateInputs(intervalAmount, period, minHours), intervalAmount = _ref[0], period = _ref[1], minHours = _ref[2];
		start = this._trueStart.clone().startOf(period);
		end = this._trueEnd.clone().startOf(period);
		hasNext = (function(_this) {
			return function() {
			return (!_this.allDay && start <= end && (!minHours || !start.isSame(end) || _this.end.hours() > minHours)) || (_this.allDay && start < end);
			};
		})(this);
		return this._iterateHelper(period, start, hasNext, intervalAmount);
		};

		Twix.prototype.iterateInner = function(intervalAmount, period) {
		var end, hasNext, start, _ref, _ref1;
		if (intervalAmount == null) {
			intervalAmount = 1;
		}
		_ref = this._prepIterateInputs(intervalAmount, period), intervalAmount = _ref[0], period = _ref[1];
		_ref1 = this._inner(period, intervalAmount), start = _ref1[0], end = _ref1[1];
		hasNext = function() {
			return start < end;
		};
		return this._iterateHelper(period, start, hasNext, intervalAmount);
		};

		Twix.prototype.humanizeLength = function() {
		if (this.allDay) {
			if (this.isSame("day")) {
			return "all day";
			} else {
			return this.start.from(this.end.clone().add(1, "day"), true);
			}
		} else {
			return this.start.from(this.end, true);
		}
		};

		Twix.prototype.asDuration = function(units) {
		var diff;
		diff = this.end.diff(this.start);
		return moment.duration(diff);
		};

		Twix.prototype.isPast = function() {
		if (this.allDay) {
			return this.end.clone().endOf("day") < moment();
		} else {
			return this.end < moment();
		}
		};

		Twix.prototype.isFuture = function() {
		if (this.allDay) {
			return this.start.clone().startOf("day") > moment();
		} else {
			return this.start > moment();
		}
		};

		Twix.prototype.isCurrent = function() {
		return !this.isPast() && !this.isFuture();
		};

		Twix.prototype.contains = function(mom) {
		if (!moment.isMoment(mom)) {
			mom = moment(mom);
		}
		return this._trueStart <= mom && this._trueEnd >= mom;
		};

		Twix.prototype.isEmpty = function() {
		return this._trueStart.isSame(this._trueEnd);
		};

		Twix.prototype.overlaps = function(other) {
		return this._trueEnd.isAfter(other._trueStart) && this._trueStart.isBefore(other._trueEnd);
		};

		Twix.prototype.engulfs = function(other) {
		return this._trueStart <= other._trueStart && this._trueEnd >= other._trueEnd;
		};

		Twix.prototype.union = function(other) {
		var allDay, newEnd, newStart;
		allDay = this.allDay && other.allDay;
		if (allDay) {
			newStart = this.start < other.start ? this.start : other.start;
			newEnd = this.end > other.end ? this.end : other.end;
		} else {
			newStart = this._trueStart < other._trueStart ? this._trueStart : other._trueStart;
			newEnd = this._trueEnd > other._trueEnd ? this._trueEnd : other._trueEnd;
		}
		return new Twix(newStart, newEnd, allDay);
		};

		Twix.prototype.intersection = function(other) {
		var allDay, newEnd, newStart;
		allDay = this.allDay && other.allDay;
		if (allDay) {
			newStart = this.start > other.start ? this.start : other.start;
			newEnd = this.end < other.end ? this.end : other.end;
		} else {
			newStart = this._trueStart > other._trueStart ? this._trueStart : other._trueStart;
			newEnd = this._trueEnd < other._trueEnd ? this._trueEnd : other._trueEnd;
		}
		return new Twix(newStart, newEnd, allDay);
		};

		Twix.prototype.xor = function() {
		var allDay, arr, endTime, i, item, last, o, open, other, others, results, start, t, _i, _j, _len, _len1, _ref;
		others = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
		open = 0;
		start = null;
		results = [];
		allDay = ((function() {
			var _i, _len, _results;
			_results = [];
			for (_i = 0, _len = others.length; _i < _len; _i++) {
			o = others[_i];
			if (o.allDay) {
				_results.push(o);
			}
			}
			return _results;
		})()).length === others.length;
		arr = [];
		_ref = [this].concat(others);
		for (i = _i = 0, _len = _ref.length; _i < _len; i = ++_i) {
			item = _ref[i];
			arr.push({
			time: item._trueStart,
			i: i,
			type: 0
			});
			arr.push({
			time: item._trueEnd,
			i: i,
			type: 1
			});
		}
		arr = arr.sort(function(a, b) {
			return a.time - b.time;
		});
		for (_j = 0, _len1 = arr.length; _j < _len1; _j++) {
			other = arr[_j];
			if (other.type === 1) {
			open -= 1;
			}
			if (open === other.type) {
			start = other.time;
			}
			if (open === (other.type + 1) % 2) {
			if (start) {
				last = results[results.length - 1];
				if (last && last.end.isSame(start)) {
				last.end = other.time;
				} else {
				endTime = allDay ? other.time.clone().subtract(1, 'd') : other.time;
				t = new Twix(start, endTime, allDay);
				if (!t.isEmpty()) {
					results.push(t);
				}
				}
			}
			start = null;
			}
			if (other.type === 0) {
			open += 1;
			}
		}
		return results;
		};

		Twix.prototype.difference = function() {
		var others, t, _i, _len, _ref, _results;
		others = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
		_ref = this.xor.apply(this, others).map((function(_this) {
			return function(i) {
			return _this.intersection(i);
			};
		})(this));
		_results = [];
		for (_i = 0, _len = _ref.length; _i < _len; _i++) {
			t = _ref[_i];
			if (!t.isEmpty() && t.isValid()) {
			_results.push(t);
			}
		}
		return _results;
		};

		Twix.prototype.split = function() {
		var args, dur, end, final, i, mom, start, time, times, vals;
		args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
		end = start = this._trueStart.clone();
		if (moment.isDuration(args[0])) {
			dur = args[0];
		} else if ((!moment.isMoment(args[0]) && !isArray(args[0]) && typeof args[0] === "object") || (typeof args[0] === "number" && typeof args[1] === "string")) {
			dur = moment.duration(args[0], args[1]);
		} else if (isArray(args[0])) {
			times = args[0];
		} else {
			times = args;
		}
		if (times) {
			times = (function() {
			var _i, _len, _results;
			_results = [];
			for (_i = 0, _len = times.length; _i < _len; _i++) {
				time = times[_i];
				_results.push(moment(time));
			}
			return _results;
			})();
			times = ((function() {
			var _i, _len, _results;
			_results = [];
			for (_i = 0, _len = times.length; _i < _len; _i++) {
				mom = times[_i];
				if (mom.isValid() && mom >= start) {
				_results.push(mom);
				}
			}
			return _results;
			})()).sort();
		}
		if ((dur && dur.asMilliseconds() === 0) || (times && times.length === 0)) {
			return [this];
		}
		vals = [];
		i = 0;
		final = this._trueEnd;
		while (start < final && ((times == null) || times[i])) {
			end = dur ? start.clone().add(dur) : times[i].clone();
			end = moment.min(final, end);
			if (!start.isSame(end)) {
			vals.push(moment.twix(start, end));
			}
			start = end;
			i += 1;
		}
		if (!end.isSame(this._trueEnd) && times) {
			vals.push(moment.twix(end, this._trueEnd));
		}
		return vals;
		};

		Twix.prototype.isValid = function() {
		return this._trueStart <= this._trueEnd;
		};

		Twix.prototype.equals = function(other) {
		return (other instanceof Twix) && this.allDay === other.allDay && this.start.valueOf() === other.start.valueOf() && this.end.valueOf() === other.end.valueOf();
		};

		Twix.prototype.toString = function() {
		var _ref;
		return "{start: " + (this.start.format()) + ", end: " + (this.end.format()) + ", allDay: " + ((_ref = this.allDay) != null ? _ref : {
			"true": "false"
		}) + "}";
		};

		Twix.prototype.simpleFormat = function(momentOpts, inopts) {
		var options, s;
		options = {
			allDay: "(all day)",
			template: Twix.formatTemplate
		};
		Twix._extend(options, inopts || {});
		s = options.template(this.start.format(momentOpts), this.end.format(momentOpts));
		if (this.allDay && options.allDay) {
			s += " " + options.allDay;
		}
		return s;
		};

		Twix.prototype.format = function(inopts) {
		var common_bucket, end_bucket, fold, format, fs, global_first, goesIntoTheMorning, needDate, options, process, start_bucket, together, _i, _len;
		this._lazyLang();
		if (this.isEmpty()) {
			return "";
		}
		options = {
			groupMeridiems: true,
			spaceBeforeMeridiem: true,
			showDate: true,
			showDayOfWeek: false,
			twentyFourHour: this.langData.twentyFourHour,
			implicitMinutes: true,
			implicitYear: true,
			yearFormat: "YYYY",
			monthFormat: "MMM",
			weekdayFormat: "ddd",
			dayFormat: "D",
			meridiemFormat: "A",
			hourFormat: "h",
			minuteFormat: "mm",
			allDay: "all day",
			explicitAllDay: false,
			lastNightEndsAt: 0,
			template: Twix.formatTemplate
		};
		Twix._extend(options, inopts || {});
		fs = [];
		if (options.twentyFourHour) {
			options.hourFormat = options.hourFormat.replace("h", "H");
		}
		goesIntoTheMorning = options.lastNightEndsAt > 0 && !this.allDay && this.end.clone().startOf("day").valueOf() === this.start.clone().add(1, "day").startOf("day").valueOf() && this.start.hours() > 12 && this.end.hours() < options.lastNightEndsAt;
		needDate = options.showDate || (!this.isSame("day") && !goesIntoTheMorning);
		if (this.allDay && this.isSame("day") && (!options.showDate || options.explicitAllDay)) {
			fs.push({
			name: "all day simple",
			fn: this._formatFn('allDaySimple', options),
			pre: this._formatPre('allDaySimple', options),
			slot: this._formatSlot('allDaySimple')
			});
		}
		if (needDate && (!options.implicitYear || this.start.year() !== moment().year() || !this.isSame("year"))) {
			fs.push({
			name: "year",
			fn: this._formatFn('year', options),
			pre: this._formatPre('year', options),
			slot: this._formatSlot('year')
			});
		}
		if (!this.allDay && needDate) {
			fs.push({
			name: "all day month",
			fn: this._formatFn('allDayMonth', options),
			ignoreEnd: function() {
				return goesIntoTheMorning;
			},
			pre: this._formatPre('allDayMonth', options),
			slot: this._formatSlot('allDayMonth')
			});
		}
		if (this.allDay && needDate) {
			fs.push({
			name: "month",
			fn: this._formatFn('month', options),
			pre: this._formatPre('month', options),
			slot: this._formatSlot('month')
			});
		}
		if (this.allDay && needDate) {
			fs.push({
			name: "date",
			fn: this._formatFn('date', options),
			pre: this._formatPre('date', options),
			slot: this._formatSlot('date')
			});
		}
		if (needDate && options.showDayOfWeek) {
			fs.push({
			name: "day of week",
			fn: this._formatFn('dayOfWeek', options),
			pre: this._formatPre('dayOfWeek', options),
			slot: this._formatSlot('dayOfWeek')
			});
		}
		if (options.groupMeridiems && !options.twentyFourHour && !this.allDay) {
			fs.push({
			name: "meridiem",
			fn: this._formatFn('meridiem', options),
			pre: this._formatPre('meridiem', options),
			slot: this._formatSlot('meridiem')
			});
		}
		if (!this.allDay) {
			fs.push({
			name: "time",
			fn: this._formatFn('time', options),
			pre: this._formatPre('time', options),
			slot: this._formatSlot('time')
			});
		}
		start_bucket = [];
		end_bucket = [];
		common_bucket = [];
		together = true;
		process = (function(_this) {
			return function(format) {
			var end_str, start_group, start_str;
			start_str = format.fn(_this.start);
			end_str = format.ignoreEnd && format.ignoreEnd() ? start_str : format.fn(_this.end);
			start_group = {
				format: format,
				value: function() {
				return start_str;
				}
			};
			if (end_str === start_str && together) {
				return common_bucket.push(start_group);
			} else {
				if (together) {
				together = false;
				common_bucket.push({
					format: {
					slot: format.slot,
					pre: ""
					},
					value: function() {
					return options.template(fold(start_bucket), fold(end_bucket, true).trim());
					}
				});
				}
				start_bucket.push(start_group);
				return end_bucket.push({
				format: format,
				value: function() {
					return end_str;
				}
				});
			}
			};
		})(this);
		for (_i = 0, _len = fs.length; _i < _len; _i++) {
			format = fs[_i];
			process(format);
		}
		global_first = true;
		fold = (function(_this) {
			return function(array, skip_pre) {
			var local_first, section, str, _j, _len1, _ref;
			local_first = true;
			str = "";
			_ref = array.sort(function(a, b) {
				return a.format.slot - b.format.slot;
			});
			for (_j = 0, _len1 = _ref.length; _j < _len1; _j++) {
				section = _ref[_j];
				if (!global_first) {
				if (local_first && skip_pre) {
					str += " ";
				} else {
					str += section.format.pre;
				}
				}
				str += section.value();
				global_first = false;
				local_first = false;
			}
			return str;
			};
		})(this);
		return fold(common_bucket);
		};

		Twix.prototype._iterateHelper = function(period, iter, hasNext, intervalAmount) {
		if (intervalAmount == null) {
			intervalAmount = 1;
		}
		return {
			next: (function(_this) {
			return function() {
				var val;
				if (!hasNext()) {
				return null;
				} else {
				val = iter.clone();
				iter.add(intervalAmount, period);
				return val;
				}
			};
			})(this),
			hasNext: hasNext
		};
		};

		Twix.prototype._prepIterateInputs = function() {
		var inputs, intervalAmount, minHours, period, _ref, _ref1;
		inputs = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
		if (typeof inputs[0] === "number") {
			return inputs;
		}
		if (typeof inputs[0] === "string") {
			period = inputs.shift();
			intervalAmount = (_ref = inputs.pop()) != null ? _ref : 1;
			if (inputs.length) {
			minHours = (_ref1 = inputs[0]) != null ? _ref1 : false;
			}
		}
		if (moment.isDuration(inputs[0])) {
			period = 'ms';
			intervalAmount = inputs[0].as(period);
		}
		return [intervalAmount, period, minHours];
		};

		Twix.prototype._inner = function(period, intervalAmount) {
		var durationCount, durationPeriod, end, modulus, start;
		if (period == null) {
			period = "ms";
		}
		if (intervalAmount == null) {
			intervalAmount = 1;
		}
		start = this._trueStart.clone();
		end = this._trueEnd.clone();
		if (start > start.clone().startOf(period)) {
			start.startOf(period).add(intervalAmount, period);
		}
		if (end < end.clone().endOf(period)) {
			end.startOf(period);
		}
		durationPeriod = start.twix(end).asDuration(period);
		durationCount = durationPeriod.get(period);
		modulus = durationCount % intervalAmount;
		end.subtract(modulus, period);
		return [start, end];
		};

		Twix.prototype._lazyLang = function() {
		var e, langData, languages, _ref;
		langData = this.start.localeData();
		if ((langData != null) && this.end.locale()._abbr !== langData._abbr) {
			this.end.locale(langData._abbr);
		}
		if ((this.langData != null) && this.langData._abbr === langData._abbr) {
			return;
		}
		if (hasModule && !(languagesLoaded || langData._abbr === "en")) {
			try {
			languages = require("./lang");
			languages(moment, Twix);
			} catch (_error) {
			e = _error;
			}
			languagesLoaded = true;
		}
		return this.langData = (_ref = langData != null ? langData._twix : void 0) != null ? _ref : Twix.defaults;
		};

		Twix.prototype._formatFn = function(name, options) {
		return this.langData[name].fn(options);
		};

		Twix.prototype._formatSlot = function(name) {
		return this.langData[name].slot;
		};

		Twix.prototype._formatPre = function(name, options) {
		if (typeof this.langData[name].pre === "function") {
			return this.langData[name].pre(options);
		} else {
			return this.langData[name].pre;
		}
		};

		Twix.prototype.sameDay = deprecate("sameDay", "isSame('day')", function() {
		return this.isSame("day");
		});

		Twix.prototype.sameYear = deprecate("sameYear", "isSame('year')", function() {
		return this.isSame("year");
		});

		Twix.prototype.countDays = deprecate("countDays", "countOuter('days')", function() {
		return this.countOuter("days");
		});

		Twix.prototype.daysIn = deprecate("daysIn", "iterate('days' [,minHours])", function(minHours) {
		return this.iterate('days', minHours);
		});

		Twix.prototype.past = deprecate("past", "isPast()", function() {
		return this.isPast();
		});

		Twix.prototype.duration = deprecate("duration", "humanizeLength()", function() {
		return this.humanizeLength();
		});

		Twix.prototype.merge = deprecate("merge", "union(other)", function(other) {
		return this.union(other);
		});

		return Twix;

	})();
	Twix._extend(moment._locale, {
		_twix: Twix.defaults
	});
	Twix.formatTemplate = function(leftSide, rightSide) {
		return "" + leftSide + " - " + rightSide;
	};
	moment.twix = function() {
		return (function(func, args, ctor) {
		ctor.prototype = func.prototype;
		var child = new ctor, result = func.apply(child, args);
		return Object(result) === result ? result : child;
		})(Twix, arguments, function(){});
	};
	moment.fn.twix = function() {
		return (function(func, args, ctor) {
		ctor.prototype = func.prototype;
		var child = new ctor, result = func.apply(child, args);
		return Object(result) === result ? result : child;
		})(Twix, [this].concat(__slice.call(arguments)), function(){});
	};
	moment.fn.forDuration = function(duration, allDay) {
		return new Twix(this, this.clone().add(duration), allDay);
	};
	moment.duration.fn.afterMoment = function(startingTime, allDay) {
		return new Twix(startingTime, moment(startingTime).clone().add(this), allDay);
	};
	moment.duration.fn.beforeMoment = function(startingTime, allDay) {
		return new Twix(moment(startingTime).clone().subtract(this), startingTime, allDay);
	};
	moment.twixClass = Twix;
	return Twix;
	};

	if (hasModule) {
	module.exports = makeTwix(require("moment"));
	}

	if (typeof define === "function") {
	define("twix", ["moment"], function(moment) {
		return makeTwix(moment);
	});
	}

	if (this.moment != null) {
	this.Twix = makeTwix(this.moment);
	}

}).call(this);
