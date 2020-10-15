!function(e){var n={};function r(t){if(n[t])return n[t].exports;var o=n[t]={i:t,l:!1,exports:{}};return e[t].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=e,r.c=n,r.d=function(e,n,t){r.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:t})},r.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(n,"a",n),n},r.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},r.p="",r(r.s=812)}({242:function(e,n,r){"use strict";r.d(n,"b",function(){return c});var t=r(813);r.d(n,"a",function(){return t.a});var o=Object(t.a)(),c=(o.addAction,o.addFilter,o.removeAction,o.removeFilter,o.hasAction,o.hasFilter,o.removeAllActions,o.removeAllFilters,o.doAction);o.applyFilters,o.currentAction,o.currentFilter,o.doingAction,o.doingFilter,o.didAction,o.didFilter,o.actions,o.filters},243:function(e,n,r){"use strict";n.a=function(e){return"string"!=typeof e||""===e?(console.error("The hook name must be a non-empty string."),!1):/^__/.test(e)?(console.error("The hook name cannot begin with `__`."),!1):!!/^[a-zA-Z][a-zA-Z0-9_.-]*$/.test(e)||(console.error("The hook name can only contain numbers, letters, dashes, periods and underscores."),!1)}},373:function(e,n,r){"use strict";n.a=function(e){return"string"!=typeof e||""===e?(console.error("The namespace must be a non-empty string."),!1):!!/^[a-zA-Z][a-zA-Z0-9_.\-\/]*$/.test(e)||(console.error("The namespace can only contain numbers, letters, dashes, periods, underscores and slashes."),!1)}},812:function(e,n,r){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var t=r(242);window.wedevsPMWPHook=Object(t.a)()},813:function(e,n,r){"use strict";var t=r(814),o=r(815),c=r(816),i=r(817),u=r(818),a=r(819),s=r(820);n.a=function(){var e=Object.create(null),n=Object.create(null);return e.__current=[],n.__current=[],{addAction:Object(t.a)(e),addFilter:Object(t.a)(n),removeAction:Object(o.a)(e),removeFilter:Object(o.a)(n),hasAction:Object(c.a)(e),hasFilter:Object(c.a)(n),removeAllActions:Object(o.a)(e,!0),removeAllFilters:Object(o.a)(n,!0),doAction:Object(i.a)(e),applyFilters:Object(i.a)(n,!0),currentAction:Object(u.a)(e),currentFilter:Object(u.a)(n),doingAction:Object(a.a)(e),doingFilter:Object(a.a)(n),didAction:Object(s.a)(e),didFilter:Object(s.a)(n),actions:e,filters:n}}},814:function(e,n,r){"use strict";var t=r(373),o=r(243),c=r(242);n.a=function(e){return function(n,r,i){var u=arguments.length>3&&void 0!==arguments[3]?arguments[3]:10;if(Object(o.a)(n)&&Object(t.a)(r))if("function"==typeof i)if("number"==typeof u){var a={callback:i,priority:u,namespace:r};if(e[n]){var s,l=e[n].handlers;for(s=l.length;s>0&&!(u>=l[s-1].priority);s--);s===l.length?l[s]=a:l.splice(s,0,a),(e.__current||[]).forEach(function(e){e.name===n&&e.currentIndex>=s&&e.currentIndex++})}else e[n]={handlers:[a],runs:0};"hookAdded"!==n&&Object(c.b)("hookAdded",n,r,i,u)}else console.error("If specified, the hook priority must be a number.");else console.error("The hook callback must be a function.")}}},815:function(e,n,r){"use strict";var t=r(373),o=r(243),c=r(242);n.a=function(e,n){return function(r,i){if(Object(o.a)(r)&&(n||Object(t.a)(i))){if(!e[r])return 0;var u=0;if(n)u=e[r].handlers.length,e[r]={runs:e[r].runs,handlers:[]};else for(var a=e[r].handlers,s=function(n){a[n].namespace===i&&(a.splice(n,1),u++,(e.__current||[]).forEach(function(e){e.name===r&&e.currentIndex>=n&&e.currentIndex--}))},l=a.length-1;l>=0;l--)s(l);return"hookRemoved"!==r&&Object(c.b)("hookRemoved",r,i),u}}}},816:function(e,n,r){"use strict";n.a=function(e){return function(n){return n in e}}},817:function(e,n,r){"use strict";n.a=function(e,n){return function(r){e[r]||(e[r]={handlers:[],runs:0}),e[r].runs++;for(var t=e[r].handlers,o=arguments.length,c=new Array(o>1?o-1:0),i=1;i<o;i++)c[i-1]=arguments[i];if(!t||!t.length)return n?c[0]:void 0;var u={name:r,currentIndex:0};for(e.__current.push(u);u.currentIndex<t.length;){var a=t[u.currentIndex].callback.apply(null,c);n&&(c[0]=a),u.currentIndex++}return e.__current.pop(),n?c[0]:void 0}}},818:function(e,n,r){"use strict";n.a=function(e){return function(){return e.__current&&e.__current.length?e.__current[e.__current.length-1].name:null}}},819:function(e,n,r){"use strict";n.a=function(e){return function(n){return void 0===n?void 0!==e.__current[0]:!!e.__current[0]&&n===e.__current[0].name}}},820:function(e,n,r){"use strict";var t=r(243);n.a=function(e){return function(n){if(Object(t.a)(n))return e[n]&&e[n].runs?e[n].runs:0}}}});