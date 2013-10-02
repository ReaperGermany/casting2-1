// Позаимствовано из библиотеки Prototype

function objectExtend (destination, source) {
  for (var property in source)
    destination[property] = source[property];
  return destination;
};

function $A(iterable) {
  if (!iterable) return [];
  if (iterable.toArray) return iterable.toArray();
  var length = iterable.length || 0, results = new Array(length);
  while (length--) results[length] = iterable[length];
  return results;
}

objectExtend(Function.prototype, {
//  argumentNames: function() {
//    var names = this.toString().match(/^[\s\(]*function[^(]*\(([^\)]*)\)/)[1]
//      .replace(/\s+/g, '').split(',');
//    return names.length == 1 && !names[0] ? [] : names;
//  },

  bind: function() {
    if (arguments.length < 2 && typeof arguments[0] == "undefined") return this;
    var __method = this, args = $A(arguments), object = args.shift();
    return function() {
      return __method.apply(object, args.concat($A(arguments)));
    }
  }//,

//  bindAsEventListener: function() {
//    var __method = this, args = $A(arguments), object = args.shift();
//    return function(event) {
//      return __method.apply(object, [event || window.event].concat(args));
//    }
//  },
//
//  curry: function() {
//    if (!arguments.length) return this;
//    var __method = this, args = $A(arguments);
//    return function() {
//      return __method.apply(this, args.concat($A(arguments)));
//    }
//  },
//
//  delay: function() {
//    var __method = this, args = $A(arguments), timeout = args.shift() * 1000;
//    return window.setTimeout(function() {
//      return __method.apply(__method, args);
//    }, timeout);
//  },
//
//  defer: function() {
//    var args = [0.01].concat($A(arguments));
//    return this.delay.apply(this, args);
//  },
//
//  wrap: function(wrapper) {
//    var __method = this;
//    return function() {
//      return wrapper.apply(this, [__method.bind(this)].concat($A(arguments)));
//    }
//  },
//
//  methodize: function() {
//    if (this._methodized) return this._methodized;
//    var __method = this;
//    return this._methodized = function() {
//      return __method.apply(null, [this].concat($A(arguments)));
//    };
//  }
});

