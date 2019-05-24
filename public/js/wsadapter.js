/*!
 * JavaScript Library v0.0.2
 * http://webstore.mn/
 *
 * Copyright 2011, Sainzaya Batkhuu
 *
 * Date: Fri Jun 8 11:21:34 2012 -0800
 */


var WebStoreAdapter = (function () {
return {
	merge: function () { // the built-in prototype merge function doesn't do deep copy
		function doCopy(copy, original) {
			var value, key;

			for (key in original) {
				value = original[key];
				if (value && typeof value === 'object' && value.constructor !== Array &&
						typeof value.nodeType !== 'number') {
					copy[key] = doCopy(copy[key] || {}, value); // copy

				} else {
					copy[key] = original[key];
				}
			}
			return copy;
		}

		function merge() {
			var args = arguments,
				i,
				retVal = {};

			for (i = 0; i < args.length; i++) {
				retVal = doCopy(retVal, args[i]);

			}
			return retVal;
		}

		return merge.apply(this, arguments);
	}
};
}());


