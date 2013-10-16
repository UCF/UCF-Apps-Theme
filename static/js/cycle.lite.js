s, moveForward) {
        var val = moveForward ? 1 : -1;
        var els = opts.elements;
        var p = opts.$cont[0], timeout = p.cycleTimeout;
        if (timeout) {
                clearTimeout(timeout);
                p.cycleTimeout = 0;
        }
        if (opts.random && val < 0) {
                // move back to the previously display slide
                opts.randomIndex--;
                if (--opts.randomIndex == -2)
                        opts.randomIndex = els.length-2;
                else if (opts.randomIndex == -1)
                        opts.randomIndex = els.length-1;
                opts.nextSlide = opts.randomMap[opts.randomIndex];
        }
        else if (opts.random) {
                opts.nextSlide = opts.randomMap[opts.randomIndex];
        }
        else {
                opts.nextSlide = opts.currSlide + val;
                if (opts.nextSlide < 0) {
                        if (opts.nowrap) return false;
                        opts.nextSlide = els.length - 1;
                }
                else if (opts.nextSlide >= els.length) {
                        if (opts.nowrap) return false;
                        opts.nextSlide = 0;
                }
        }

        var cb = opts.onPrevNextEvent || opts.prevNextClick; // prevNextClick is deprecated
        if ($.isFunction(cb))
                cb(val > 0, opts.nextSlide, els[opts.nextSlide]);
        go(els, opts, 1, moveForward);
        return false;
}

function buildPager(els, opts) {
        var $p = $(opts.pager);
        $.each(els, function(i,o) {
                $.fn.cycle.createPagerAnchor(i,o,$p,els,opts);
        });
        opts.updateActivePagerLink(opts.pager, opts.startingSlide, opts.activePagerClass);
}

$.fn.cycle.createPagerAnchor = function(i, el, $p, els, opts) {
        var a;
        if ($.isFunction(opts.pagerAnchorBuilder)) {
                a = opts.pagerAnchorBuilder(i,el);
                debug('pagerAnchorBuilder('+i+', el) returned: ' + a);
        }
        else
                a = '<a href="#">'+(i+1)+'</a>';
                
        if (!a)
                return;
        var $a = $(a);
        // don't reparent if anchor is in the dom
        if ($a.parents('body').length === 0) {
                var arr = [];
                if ($p.length > 1) {
                        $p.each(function() {
                                var $clone = $a.clone(true);
                                $(this).append($clone);
                                arr.push($clone[0]);
                        });
                        $a = $(arr);
                }
                else {
                        $a.appendTo($p);
                }
        }

        opts.pagerAnchors =  opts.pagerAnchors || [];
        opts.pagerAnchors.push($a);
        
        var pagerFn = function(e) {
                e.preventDefault();
                opts.nextSlide = i;
                var p = opts.$cont[0], timeout = p.cycleTimeout;
                if (timeout) {
                        clearTimeout(timeout);
                        p.cycleTimeout = 0;
                }
                var cb = opts.onPagerEvent || opts.pagerClick; // pagerClick is deprecated
                if ($.isFunction(cb))
                        cb(opts.nextSlide, els[opts.nextSlide]);
                go(els,opts,1,opts.currSlide < i); // trigger the trans
//                return false; // <== allow bubble
        };
        
        if ( /mouseenter|mouseover/i.test(opts.pagerEvent) ) {
                $a.hover(pagerFn, function(){/* no-op */} );
        }
        else {
                $a.bind(opts.pagerEvent, pagerFn);
        }
        
        if ( ! /^click/.test(opts.pagerEvent) && !opts.allowPagerClickBubble)
                $a.bind('click.cycle', function(){return false;}); // suppress click
        
        var cont = opts.$cont[0];
        var pauseFlag = false; // https://github.com/malsup/cycle/issues/44
        if (opts.pauseOnPagerHover) {
                $a.hover(
                        function() { 
                                pauseFlag = true;
                                cont.cyclePause++; 
                                triggerPause(cont,true,true);
                        }, function() { 
                                if (pauseFlag)
                                        cont.cyclePause--; 
                                triggerPause(cont,true,true);
                        } 
                );
        }
};

// helper fn to calculate the number of slides between the current and the next
$.fn.cycle.hopsFromLast = function(opts, fwd) {
        var hops, l = opts.lastSlide, c = opts.currSlide;
        if (fwd)
                hops = c > l ? c - l : opts.slideCount - l;
        else
                hops = c < l ? l - c : l + opts.slideCount - c;
        return hops;
};

// fix clearType problems in ie6 by setting an explicit bg color
// (otherwise text slides look horrible during a fade transition)
function clearTypeFix($slides) {
        debug('applying clearType background-color hack');
        function hex(s) {
                s = parseInt(s,10).toString(16);
                return s.length < 2 ? '0'+s : s;
        }
        function getBg(e) {
                for ( ; e && e.nodeName.toLowerCase() != 'html'; e = e.parentNode) {
                        var v = $.css(e,'background-color');
                        if (v && v.indexOf('rgb') >= 0 ) {
                                var rgb = v.match(/\d+/g);
                                return '#'+ hex(rgb[0]) + hex(rgb[1]) + hex(rgb[2]);
                        }
                        if (v && v != 'transparent')
                                return v;
                }
                return '#ffffff';
        }
        $slides.each(function() { $(this).css('background-color', getBg(this)); });
}

// reset common props before the next transition
$.fn.cycle.commonReset = function(curr,next,opts,w,h,rev) {
        $(opts.elements).not(curr).hide();
        if (typeof opts.cssBefore.opacity == 'undefined')
                opts.cssBefore.opacity = 1;
        opts.cssBefore.display = 'block';
        if (opts.slideResize && w !== false && next.cycleW > 0)
                opts.cssBefore.width = next.cycleW;
        if (opts.slideResize && h !== false && next.cycleH > 0)
                opts.cssBefore.height = next.cycleH;
        opts.cssAfter = opts.cssAfter || {};
        opts.cssAfter.display = 'none';
        $(curr).css('zIndex',opts.slideCount + (rev === true ? 1 : 0));
        $(next).css('zIndex',opts.slideCount + (rev === true ? 0 : 1));
};

// the actual fn for effecting a transition
$.fn.cycle.custom = function(curr, next, opts, cb, fwd, speedOverride) {
        var $l = $(curr), $n = $(next);
        var speedIn = opts.speedIn, speedOut = opts.speedOut, easeIn = opts.easeIn, easeOut = opts.easeOut;
        $n.css(opts.cssBefore);
        if (speedOverride) {
                if (typeof speedOverride == 'number')
                        speedIn = speedOut = speedOverride;
                else
                        speedIn = speedOut = 1;
                easeIn = easeOut = null;
        }
        var fn = function() {
                $n.animate(opts.animIn, speedIn, easeIn, function() {
                        cb();
                });
        };
        $l.animate(opts.animOut, speedOut, easeOut, function() {
                $l.css(opts.cssAfter);
                if (!opts.sync) 
                        fn();
        });
        if (opts.sync) fn();
};

// transition definitions - only fade is defined here, transition pack defines the rest
$.fn.cycle.transitions = {
        fade: function($cont, $slides, opts) {
                $slides.not(':eq('+opts.currSlide+')').css('opacity',0);
                opts.before.push(function(curr,next,opts) {
                        $.fn.cycle.commonReset(curr,next,opts);
                        opts.cssBefore.opacity = 0;
                });
                opts.animIn           = { opacity: 1 };
                opts.animOut   = { opacity: 0 };
                opts.cssBefore = { top: 0, left: 0 };
        }
};

$.fn.cycle.ver = function() { return ver; };

// override these globally if you like (they are all optional)
$.fn.cycle.defaults = {
    activePagerClass: 'activeSlide', // class name used for the active pager link
    after:            null,     // transition callback (scope set to element that was shown):  function(currSlideElement, nextSlideElement, options, forwardFlag)
    allowPagerClickBubble: false, // allows or prevents click event on pager anchors from bubbling
    animIn:           null,     // properties that define how the slide animates in
    animOut:          null,     // properties that define how the slide animates out
    aspect:           false,    // preserve aspect ratio during fit resizing, cropping if necessary (must be used with fit option)
    autostop:         0,        // true to end slideshow after X transitions (where X == slide count)
    autostopCount:    0,        // number of transitions (optionally used with autostop to define X)
    backwards:        false,    // true to start slideshow at last slide and move backwards through the stack
    before:           null,     // transition callback (scope set to element to be shown):     function(currSlideElement, nextSlideElement, options, forwardFlag)
    center:           null,     // set to true to have cycle add top/left margin to each slide (use with width and height options)
    cleartype:        !$.support.opacity,  // true if clearType corrections should be applied (for IE)
    cleartypeNoBg:    false,    // set to true to disable extra cleartype fixing (leave false to force background color setting on slides)
    containerResize:  1,        // resize container to fit largest slide
    continuous:       0,        // true to start next transition immediately after current one completes
    cssAfter:         null,     // properties that defined the state of the slide after transitioning out
    cssBefore:        null,     // properties that define the initial state of the slide before transitioning in
    delay:            0,        // additional delay (in ms) for first transition (hint: can be negative)
    easeIn:           null,     // easing for "in" transition
    easeOut:          null,     // easing for "out" transition
    easing:           null,     // easing method for both in and out transitions
    end:              null,     // callback invoked when the slideshow terminates (use with autostop or nowrap options): function(options)
    fastOnEvent:      0,        // force fast transitions when triggered manually (via pager or prev/next); value == time in ms
    fit:              0,        // force slides to fit container
    fx:               'fade',   // name of transition effect (or comma separated names, ex: 'fade,scrollUp,shuffle')
    fxFn:             null,     // function used to control the transition: function(currSlideElement, nextSlideElement, options, afterCalback, forwardFlag)
    height:           'auto',   // container height (if the 'fit' option is true, the slides will be set to this height as well)
    manualTrump:      true,     // causes manual transition to stop an active transition instead of being ignored
    metaAttr:         'cycle',  // data- attribute that holds the option data for the slideshow
    next:             null,     // element, jQuery object, or jQuery selector string for the element to use as event trigger for next slide
    nowrap:           0,        // true to prevent slideshow from wrapping
    onPagerEvent:     null,     // callback fn for pager events: function(zeroBasedSlideIndex, slideElement)
    onPrevNextEvent:  null,     // callback fn for prev/next events: function(isNext, zeroBasedSlideIndex, slideElement)
    pager:            null,     // element, jQuery object, or jQuery selector string for the element to use as pager container
    pagerAnchorBuilder: null,   // callback fn for building anchor links:  function(index, DOMelement)
    pagerEvent:       'click.cycle', // name of event which drives the pager navigation
    pause:            0,        // true to enable "pause on hover"
    pauseOnPagerHover: 0,       // true to pause when hovering over pager link
    prev:             null,     // element, jQuery object, or jQuery selector string for the element to use as event trigger for previous slide
    prevNextEvent:    'click.cycle',// event which drives the manual transition to the previous or next slide
    random:           0,        // true for random, false for sequence (not applicable to shuffle fx)
    randomizeEffects: 1,        // valid when multiple effects are used; true to make the effect sequence random
    requeueOnImageNotLoaded: true, // requeue the slideshow if any image slides are not yet loaded
    requeueTimeout:   250,      // ms delay for requeue
    rev:              0,        // causes animations to transition in reverse (for effects that support it such as scrollHorz/scrollVert/shuffle)
    shuffle:          null,     // coords for shuffle animation, ex: { top:15, left: 200 }
    skipInitializationCallbacks: false, // set to true to disable the first before/after callback that occurs prior to any transition
    slideExpr:        null,     // expression for selecting slides (if something other than all children is required)
    slideResize:      1,        // force slide width/height to fixed size before every transition
    speed:            1000,     // speed of the transition (any valid fx speed value)
    speedIn:          null,     // speed of the 'in' transition
    speedOut:         null,     // speed of the 'out' transition
    startingSlide:    undefined,// zero-based index of the first slide to be displayed
    sync:             1,        // true if in/out transitions should occur simultaneously
    timeout:          4000,     // milliseconds between slide transitions (0 to disable auto advance)
    timeoutFn:        null,     // callback for determining per-slide timeout value:  function(currSlideElement, nextSlideElement, options, forwardFlag)
    updateActivePagerLink: null,// callback fn invoked to update the active pager link (adds/removes activePagerClass style)
    width:            null      // container width (if the 'fit' option is true, the slides will be set to this width as well)
};

})(jQuery);


/*!
 * jQuery Cycle Plugin Transition Definitions
 * This script is a plugin for the jQuery Cycle Plugin
 * Examples and documentation at: http://malsup.com/jquery/cycle/
 * Copyright (c) 2007-2010 M. Alsup
 * Version:         2.73
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */
(function($) {
"use strict";

//
// These functions define slide initialization and properties for the named
// transitions. To save file size feel free to remove any of these that you
// don't need.
//
$.fn.cycle.transitions.none = function($cont, $slides, opts) {
        opts.fxFn = function(curr,next,opts,after){
                $(next).show();
                $(curr).hide();
                after();
        };
};

// not a cross-fade, fadeout only fades out the top slide
$.fn.cycle.transitions.fadeout = function($cont, $slides, opts) {
        $slides.not(':eq('+opts.currSlide+')').css({ display: 'block', 'opacity': 1 });
        opts.before.push(function(curr,next,opts,w,h,rev) {
                $(curr).css('zIndex',opts.slideCount + (rev !== true ? 1 : 0));
                $(next).css('zIndex',opts.slideCount + (rev !== true ? 0 : 1));
        });
        opts.animIn.opacity = 1;
        opts.animOut.opacity = 0;
        opts.cssBefore.opacity = 1;
        opts.cssBefore.display = 'block';
        opts.cssAfter.zIndex = 0;
};

// scrollUp/Down/Left/Right
$.fn.cycle.transitions.scrollUp = function($cont, $slides, opts) {
        $cont.css('overflow','hidden');
        opts.before.push($.fn.cycle.commonReset);
        var h = $cont.height();
        opts.cssBefore.top = h;
        opts.cssBefore.left = 0;
        opts.cssFirst.top = 0;
        opts.animIn.top = 0;
        opts.animOut.top = -h;
};
$.fn.cycle.transitions.scrollDown = function($cont, $slides, opts) {
        $cont.css('overflow','hidden');
        opts.before.push($.fn.cycle.commonReset);
        var h = $cont.height();
        opts.cssFirst.top = 0;
        opts.cssBefore.top = -h;
        opts.cssBefore.left = 0;
        opts.animIn.top = 0;
        opts.animOut.top = h;
};
$.fn.cycle.transitions.scrollLeft = function($cont, $slides, opts) {
        $cont.css('overflow','hidden');
        opts.before.push($.fn.cycle.commonReset);
        var w = $cont.width();
        opts.cssFirst.left = 0;
        opts.cssBefore.left = w;
        opts.cssBefore.top = 0;
        opts.animIn.left = 0;
        opts.animOut.left = 0-w;
};
$.fn.cycle.transitions.scrollRight = function($cont, $slides, opts) {
        $cont.css('overflow','hidden');
        opts.before.push($.fn.cycle.commonReset);
        var w = $cont.width();
        opts.cssFirst.left = 0;
        opts.cssBefore.left = -w;
        opts.cssBefore.top = 0;
        opts.animIn.left = 0;
        opts.animOut.left = w;
};
$.fn.cycle.transitions.scrollHorz = function($cont, $slides, opts) {
        $cont.css('overflow','hidden').width();
        opts.before.push(function(curr, next, opts, fwd) {
                if (opts.rev)
                        fwd = !fwd;
                $.fn.cycle.commonReset(curr,next,opts);
                opts.cssBefore.left = fwd ? (next.cycleW-1) : (1-next.cycleW);
                opts.animOut.left = fwd ? -curr.cycleW : curr.cycleW;
        });
        opts.cssFirst.left = 0;
        opts.cssBefore.top = 0;
        opts.animIn.left = 0;
        opts.animOut.top = 0;
};
$.fn.cycle.transitions.scrollVert = function($cont, $slides, opts) {
        $cont.css('overflow','hidden');
        opts.before.push(function(curr, next, opts, fwd) {
                if (opts.rev)
                        fwd = !fwd;
                $.fn.cycle.commonReset(curr,next,opts);
                opts.cssBefore.top = fwd ? (1-next.cycleH) : (next.cycleH-1);
                opts.animOut.top = fwd ? curr.cycleH : -curr.cycleH;
        });
        opts.cssFirst.top = 0;
        opts.cssBefore.left = 0;
        opts.animIn.top = 0;
        opts.animOut.left = 0;
};

// slideX/slideY
$.fn.cycle.transitions.slideX = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
                $(opts.elements).not(curr).hide();
                $.fn.cycle.commonReset(curr,next,opts,false,true);
                opts.animIn.width = next.cycleW;
        });
        opts.cssBefore.left = 0;
        opts.cssBefore.top = 0;
        opts.cssBefore.width = 0;
        opts.animIn.width = 'show';
        opts.animOut.width = 0;
};
$.fn.cycle.transitions.slideY = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
                $(opts.elements).not(curr).hide();
                $.fn.cycle.commonReset(curr,next,opts,true,false);
                opts.animIn.height = next.cycleH;
        });
        opts.cssBefore.left = 0;
        opts.cssBefore.top = 0;
        opts.cssBefore.height = 0;
        opts.animIn.height = 'show';
        opts.animOut.height = 0;
};

// shuffle
$.fn.cycle.transitions.shuffle = function($cont, $slides, opts) {
        var i, w = $cont.css('overflow', 'visible').width();
        $slides.css({left: 0, top: 0});
        opts.before.push(function(curr,next,opts) {
                $.fn.cycle.commonReset(curr,next,opts,true,true,true);
        });
        // only adjust speed once!
        if (!opts.speedAdjusted) {
                opts.speed = opts.speed / 2; // shuffle has 2 transitions
                opts.speedAdjusted = true;
        }
        opts.random = 0;
        opts.shuffle = opts.shuffle || {left:-w, top:15};
        opts.els = [];
        for (i=0; i < $slides.length; i++)
                opts.els.push($slides[i]);

        for (i=0; i < opts.currSlide; i++)
                opts.els.push(opts.els.shift());

        // custom transition fn (hat tip to Benjamin Sterling for this bit of sweetness!)
        opts.fxFn = function(curr, next, opts, cb, fwd) {
                if (opts.rev)
                        fwd = !fwd;
                var $el = fwd ? $(curr) : $(next);
                $(next).css(opts.cssBefore);
                var count = opts.slideCount;
                $el.animate(opts.shuffle, opts.speedIn, opts.easeIn, function() {
                        var hops = $.fn.cycle.hopsFromLast(opts, fwd);
                        for (var k=0; k < hops; k++) {
                                if (fwd)
                                        opts.els.push(opts.els.shift());
                                else
                                        opts.els.unshift(opts.els.pop());
                        }
                        if (fwd) {
                                for (var i=0, len=opts.els.length; i < len; i++)
                                        $(opts.els[i]).css('z-index', len-i+count);
                        }
                        else {
                                var z = $(curr).css('z-index');
                                $el.css('z-index', parseInt(z,10)+1+count);
                        }
                        $el.animate({left:0, top:0}, opts.speedOut, opts.easeOut, function() {
                                $(fwd ? this : curr).hide();
                                if (cb) cb();
                        });
                });
        };
        $.extend(opts.cssBefore, { display: 'block', opacity: 1, top: 0, left: 0 });
};

// turnUp/Down/Left/Right
$.fn.cycle.transitions.turnUp = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts,true,false);
                opts.cssBefore.top = next.cycleH;
                opts.animIn.height = next.cycleH;
                opts.animOut.width = next.cycleW;
        });
        opts.cssFirst.top = 0;
        opts.cssBefore.left = 0;
        opts.cssBefore.height = 0;
        opts.animIn.top = 0;
        opts.animOut.height = 0;
};
$.fn.cycle.transitions.turnDown = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts,true,false);
                opts.animIn.height = next.cycleH;
                opts.animOut.top   = curr.cycleH;
        });
        opts.cssFirst.top = 0;
        opts.cssBefore.left = 0;
        opts.cssBefore.top = 0;
        opts.cssBefore.height = 0;
        opts.animOut.height = 0;
};
$.fn.cycle.transitions.turnLeft = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts,false,true);
                opts.cssBefore.left = next.cycleW;
                opts.animIn.width = next.cycleW;
        });
        opts.cssBefore.top = 0;
        opts.cssBefore.width = 0;
        opts.animIn.left = 0;
        opts.animOut.width = 0;
};
$.fn.cycle.transitions.turnRight = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts,false,true);
                opts.animIn.width = next.cycleW;
                opts.animOut.left = curr.cycleW;
        });
        $.extend(opts.cssBefore, { top: 0, left: 0, width: 0 });
        opts.animIn.left = 0;
        opts.animOut.width = 0;
};

// zoom
$.fn.cycle.transitions.zoom = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts,false,false,true);
                opts.cssBefore.top = next.cycleH/2;
                opts.cssBefore.left = next.cycleW/2;
                $.extend(opts.animIn, { top: 0, left: 0, width: next.cycleW, height: next.cycleH });
                $.extend(opts.animOut, { width: 0, height: 0, top: curr.cycleH/2, left: curr.cycleW/2 });
        });
        opts.cssFirst.top = 0;
        opts.cssFirst.left = 0;
        opts.cssBefore.width = 0;
        opts.cssBefore.height = 0;
};

// fadeZoom
$.fn.cycle.transitions.fadeZoom = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts,false,false);
                opts.cssBefore.left = next.cycleW/2;
                opts.cssBefore.top = next.cycleH/2;
                $.extend(opts.animIn, { top: 0, left: 0, width: next.cycleW, height: next.cycleH });
        });
        opts.cssBefore.width = 0;
        opts.cssBefore.height = 0;
        opts.animOut.opacity = 0;
};

// blindX
$.fn.cycle.transitions.blindX = function($cont, $slides, opts) {
        var w = $cont.css('overflow','hidden').width();
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts);
                opts.animIn.width = next.cycleW;
                opts.animOut.left   = curr.cycleW;
        });
        opts.cssBefore.left = w;
        opts.cssBefore.top = 0;
        opts.animIn.left = 0;
        opts.animOut.left = w;
};
// blindY
$.fn.cycle.transitions.blindY = function($cont, $slides, opts) {
        var h = $cont.css('overflow','hidden').height();
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts);
                opts.animIn.height = next.cycleH;
                opts.animOut.top   = curr.cycleH;
        });
        opts.cssBefore.top = h;
        opts.cssBefore.left = 0;
        opts.animIn.top = 0;
        opts.animOut.top = h;
};
// blindZ
$.fn.cycle.transitions.blindZ = function($cont, $slides, opts) {
        var h = $cont.css('overflow','hidden').height();
        var w = $cont.width();
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts);
                opts.animIn.height = next.cycleH;
                opts.animOut.top   = curr.cycleH;
        });
        opts.cssBefore.top = h;
        opts.cssBefore.left = w;
        opts.animIn.top = 0;
        opts.animIn.left = 0;
        opts.animOut.top = h;
        opts.animOut.left = w;
};

// growX - grow horizontally from centered 0 width
$.fn.cycle.transitions.growX = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts,false,true);
                opts.cssBefore.left = this.cycleW/2;
                opts.animIn.left = 0;
                opts.animIn.width = this.cycleW;
                opts.animOut.left = 0;
        });
        opts.cssBefore.top = 0;
        opts.cssBefore.width = 0;
};
// growY - grow vertically from centered 0 height
$.fn.cycle.transitions.growY = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts,true,false);
                opts.cssBefore.top = this.cycleH/2;
                opts.animIn.top = 0;
                opts.animIn.height = this.cycleH;
                opts.animOut.top = 0;
        });
        opts.cssBefore.height = 0;
        opts.cssBefore.left = 0;
};

// curtainX - squeeze in both edges horizontally
$.fn.cycle.transitions.curtainX = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts,false,true,true);
                opts.cssBefore.left = next.cycleW/2;
                opts.animIn.left = 0;
                opts.animIn.width = this.cycleW;
                opts.animOut.left = curr.cycleW/2;
                opts.animOut.width = 0;
        });
        opts.cssBefore.top = 0;
        opts.cssBefore.width = 0;
};
// curtainY - squeeze in both edges vertically
$.fn.cycle.transitions.curtainY = function($cont, $slides, opts) {
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts,true,false,true);
                opts.cssBefore.top = next.cycleH/2;
                opts.animIn.top = 0;
                opts.animIn.height = next.cycleH;
                opts.animOut.top = curr.cycleH/2;
                opts.animOut.height = 0;
        });
        opts.cssBefore.height = 0;
        opts.cssBefore.left = 0;
};

// cover - curr slide covered by next slide
$.fn.cycle.transitions.cover = function($cont, $slides, opts) {
        var d = opts.direction || 'left';
        var w = $cont.css('overflow','hidden').width();
        var h = $cont.height();
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts);
                if (d == 'right')
                        opts.cssBefore.left = -w;
                else if (d == 'up')
                        opts.cssBefore.top = h;
                else if (d == 'down')
                        opts.cssBefore.top = -h;
                else
                        opts.cssBefore.left = w;
        });
        opts.animIn.left = 0;
        opts.animIn.top = 0;
        opts.cssBefore.top = 0;
        opts.cssBefore.left = 0;
};

// uncover - curr slide moves off next slide
$.fn.cycle.transitions.uncover = function($cont, $slides, opts) {
        var d = opts.direction || 'left';
        var w = $cont.css('overflow','hidden').width();
        var h = $cont.height();
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts,true,true,true);
                if (d == 'right')
                        opts.animOut.left = w;
                else if (d == 'up')
                        opts.animOut.top = -h;
                else if (d == 'down')
                        opts.animOut.top = h;
                else
                        opts.animOut.left = -w;
        });
        opts.animIn.left = 0;
        opts.animIn.top = 0;
        opts.cssBefore.top = 0;
        opts.cssBefore.left = 0;
};

// toss - move top slide and fade away
$.fn.cycle.transitions.toss = function($cont, $slides, opts) {
        var w = $cont.css('overflow','visible').width();
        var h = $cont.height();
        opts.before.push(function(curr, next, opts) {
                $.fn.cycle.commonReset(curr,next,opts,true,true,true);
                // provide default toss settings if animOut not provided
                if (!opts.animOut.left && !opts.animOut.top)
                        $.extend(opts.animOut, { left: w*2, top: -h/2, opacity: 0 });
                else
                        opts.animOut.opacity = 0;
        });
        opts.cssBefore.left = 0;
        opts.cssBefore.top = 0;
        opts.animIn.left = 0;
};

// wipe - clip animation
$.fn.cycle.transitions.wipe = function($cont, $slides, opts) {
        var w = $cont.css('overflow','hidden').width();
        var h = $cont.height();
        opts.cssBefore = opts.cssBefore || {};
        var clip;
        if (opts.clip) {
                if (/l2r/.test(opts.clip))
                        clip = 'rect(0px 0px '+h+'px 0px)';
                else if (/r2l/.test(opts.clip))
                        clip = 'rect(0px '+w+'px '+h+'px '+w+'px)';
                else if (/t2b/.test(opts.clip))
                        clip = 'rect(0px '+w+'px 0px 0px)';
                else if (/b2t/.test(opts.clip))
                        clip = 'rect('+h+'px '+w+'px '+h+'px 0px)';
                else if (/zoom/.test(opts.clip)) {
                        var top = parseInt(h/2,10);
                        var left = parseInt(w/2,10);
                        clip = 'rect('+top+'px '+left+'px '+top+'px '+left+'px)';
                }
        }

        opts.cssBefore.clip = opts.cssBefore.clip || clip || 'rect(0px 0px 0px 0px)';

        var d = opts.cssBefore.clip.match(/(\d+)/g);
        var t = parseInt(d[0],10), r = parseInt(d[1],10), b = parseInt(d[2],10), l = parseInt(d[3],10);

        opts.before.push(function(curr, next, opts) {
                if (curr == next) return;
                var $curr = $(curr), $next = $(next);
                $.fn.cycle.commonReset(curr,next,opts,true,true,false);
                opts.cssAfter.display = 'block';

                var step = 1, count = parseInt((opts.speedIn / 13),10) - 1;
                (function f() {
                        var tt = t ? t - parseInt(step * (t/count),10) : 0;
                        var ll = l ? l - parseInt(step * (l/count),10) : 0;
                        var bb = b < h ? b + parseInt(step * ((h-b)/count || 1),10) : h;
                        var rr = r < w ? r + parseInt(step * ((w-r)/count || 1),10) : w;
                        $next.css({ clip: 'rect('+tt+'px '+rr+'px '+bb+'px '+ll+'px)' });
                        (step++ <= count) ? setTimeout(f, 13) : $curr.css('display', 'none');
                })();
        });
        $.extend(opts.cssBefore, { display: 'block', opacity: 1, top: 0, left: 0 });
        opts.animIn           = { left: 0 };
        opts.animOut   = { left: 0 };
};

})(jQuery);