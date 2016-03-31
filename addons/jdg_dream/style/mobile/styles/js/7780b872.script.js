!function () {
	   
	  
     
	"use strict";
    function a() {
        try {
            "undefined" != typeof AudioContext ? b = new AudioContext : "undefined" != typeof webkitAudioContext ? b = new webkitAudioContext : c = !1
        } catch (a) {
            c = !1
        }
        if (!c)if ("undefined" != typeof Audio)try {
            new Audio
        } catch (a) {
            d = !0
        } else d = !0
    }

    var b = null, c = !0, d = !1;
    if (a(), c) {
        var e = "undefined" == typeof b.createGain ? b.createGainNode() : b.createGain();
        e.gain.value = 1, e.connect(b.destination)
    }
    var f = function () {
        this.init()
    };
    f.prototype = {
        init: function () {
            var a = this || g;
            return a._codecs = {}, a._howls = [], a._muted = !1, a._volume = 1, a.iOSAutoEnable = !0, a.noAudio = d, a.usingWebAudio = c, a.ctx = b, d || a._setupCodecs(), a
        }, volume: function (a) {
            var b = this || g;
            if (a = parseFloat(a), "undefined" != typeof a && a >= 0 && 1 >= a) {
                b._volume = a, c && (e.gain.value = a);
                for (var d = 0; d < b._howls.length; d++)if (!b._howls[d]._webAudio)for (var f = b._howls[d]._getSoundIds(), h = 0; h < f.length; h++) {
                    var i = b._howls[d]._soundById(f[h]);
                    i && i._node && (i._node.volume = i._volume * a)
                }
                return b
            }
            return b._volume
        }, mute: function (a) {
            var b = this || g;
            b._muted = a, c && (e.gain.value = a ? 0 : b._volume);
            for (var d = 0; d < b._howls.length; d++)if (!b._howls[d]._webAudio)for (var f = b._howls[d]._getSoundIds(), h = 0; h < f.length; h++) {
                var i = b._howls[d]._soundById(f[h]);
                i && i._node && (i._node.muted = a ? !0 : i._muted)
            }
            return b
        }, codecs: function (a) {
            return (this || g)._codecs[a]
        }, _setupCodecs: function () {
            var a = this || g, b = new Audio, c = b.canPlayType("audio/mpeg;").replace(/^no$/, "");
            return a._codecs = {
                mp3: !(!c && !b.canPlayType("audio/mp3;").replace(/^no$/, "")),
                mpeg: !!c,
                opus: !!b.canPlayType('audio/ogg; codecs="opus"').replace(/^no$/, ""),
                ogg: !!b.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/, ""),
                wav: !!b.canPlayType('audio/wav; codecs="1"').replace(/^no$/, ""),
                aac: !!b.canPlayType("audio/aac;").replace(/^no$/, ""),
                m4a: !!(b.canPlayType("audio/x-m4a;") || b.canPlayType("audio/m4a;") || b.canPlayType("audio/aac;")).replace(/^no$/, ""),
                mp4: !!(b.canPlayType("audio/x-mp4;") || b.canPlayType("audio/mp4;") || b.canPlayType("audio/aac;")).replace(/^no$/, ""),
                weba: !!b.canPlayType('audio/webm; codecs="vorbis"').replace(/^no$/, ""),
                webm: !!b.canPlayType('audio/webm; codecs="vorbis"').replace(/^no$/, "")
            }, a
        }, _enableiOSAudio: function () {
            var a = this || g;
            if (!b || !a._iOSEnabled && /iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                a._iOSEnabled = !1;
                var c = function () {
                    var d = b.createBuffer(1, 1, 22050), e = b.createBufferSource();
                    e.buffer = d, e.connect(b.destination), "undefined" == typeof e.start ? e.noteOn(0) : e.start(0), setTimeout(function () {
                        (e.playbackState === e.PLAYING_STATE || e.playbackState === e.FINISHED_STATE) && (a._iOSEnabled = !0, a.iOSAutoEnable = !1, window.removeEventListener("touchstart", c, !1))
                    }, 0)
                };
                return window.addEventListener("touchstart", c, !1), a
            }
        }
    };
    var g = new f, h = function (a) {
        var b = this;
        return a.src ? void b.init(a) : void console.error("An array of source files must be passed with any new Howl.")
    };
    h.prototype = {
        init: function (a) {
            var d = this;
            return d._autoplay = a.autoplay || !1, d._ext = a.ext || null, d._html5 = a.html5 || !1, d._muted = a.mute || !1, d._loop = a.loop || !1, d._pool = a.pool || 5, d._preload = "boolean" == typeof a.preload ? a.preload : !0, d._rate = a.rate || 1, d._sprite = a.sprite || {}, d._src = "string" != typeof a.src ? a.src : [a.src], d._volume = void 0 !== a.volume ? a.volume : 1, d._duration = 0, d._loaded = !1, d._sounds = [], d._endTimers = {}, d._onend = a.onend ? [{fn: a.onend}] : [], d._onfaded = a.onfaded ? [{fn: a.onfaded}] : [], d._onload = a.onload ? [{fn: a.onload}] : [], d._onloaderror = a.onloaderror ? [{fn: a.onloaderror}] : [], d._onpause = a.onpause ? [{fn: a.onpause}] : [], d._onplay = a.onplay ? [{fn: a.onplay}] : [], d._webAudio = c && !d._html5, "undefined" != typeof b && b && g.iOSAutoEnable && g._enableiOSAudio(), g._howls.push(d), d._preload && d.load(), d
        }, load: function () {
            var a = this, b = null;
            if (d)return void a._emit("loaderror");
            "string" == typeof a._src && (a._src = [a._src]);
            for (var c = 0; c < a._src.length; c++) {
                var e, f;
                if (a._ext && a._ext[c] ? e = a._ext[c] : (f = a._src[c], e = /^data:audio\/([^;,]+);/i.exec(f), e || (e = /\.([^.]+)$/.exec(f.split("?", 1)[0])), e && (e = e[1].toLowerCase())), g.codecs(e)) {
                    b = a._src[c];
                    break
                }
            }
            return b ? (a._src = b, new i(a), a._webAudio && k(a), a) : void a._emit("loaderror")
        }, play: function (a) {
            var c = this, d = null;
            if ("number" == typeof a)d = a, a = null; else if ("undefined" == typeof a) {
                a = "__default";
                for (var e = 0, f = 0; f < c._sounds.length; f++)c._sounds[f]._paused && !c._sounds[f]._ended && (e++, d = c._sounds[f]._id);
                1 === e ? a = null : d = null
            }
            var h = d ? c._soundById(d) : c._inactiveSound();
            if (d && !a && (a = h._sprite || "__default"), !h)return null;
            if (!c._loaded && !c._sprite[a])return c.once("load", function () {
                c.play(c._soundById(h._id) ? h._id : void 0)
            }), h._id;
            if (d && !h._paused)return h._id;
            var i = h._seek > 0 ? h._seek : c._sprite[a][0] / 1e3, j = (c._sprite[a][0] + c._sprite[a][1]) / 1e3 - i, k = !(!h._loop && !c._sprite[a][2]), l = function () {
                c._emit("end", h._id), !c._webAudio && k && c.stop(h._id).play(h._id), c._webAudio && k && (c._emit("play", h._id), h._seek = h._start || 0, h._playStart = b.currentTime, c._endTimers[h._id] = setTimeout(l, 1e3 * (h._stop - h._start) / Math.abs(c._rate))), c._webAudio && !k && (h._paused = !0, h._ended = !0, h._seek = h._start || 0, c._clearTimer(h._id), h._node.bufferSource = null), c._webAudio || k || c.stop(h._id)
            };
            c._endTimers[h._id] = setTimeout(l, 1e3 * j / Math.abs(c._rate)), h._paused = !1, h._ended = !1, h._sprite = a, h._seek = i, h._start = c._sprite[a][0] / 1e3, h._stop = (c._sprite[a][0] + c._sprite[a][1]) / 1e3, h._loop = k;
            var m = h._node;
            if (c._webAudio) {
                var n = function () {
                    c._refreshBuffer(h);
                    var a = h._muted || c._muted ? 0 : h._volume * g.volume();
                    m.gain.setValueAtTime(a, b.currentTime), h._playStart = b.currentTime, "undefined" == typeof m.bufferSource.start ? m.bufferSource.noteGrainOn(0, i, j) : m.bufferSource.start(0, i, j), c._endTimers[h._id] || (c._endTimers[h._id] = setTimeout(l, 1e3 * j / Math.abs(c._rate))), setTimeout(function () {
                        c._emit("play", h._id)
                    }, 0)
                };
                c._loaded ? n() : (c.once("load", n), c._clearTimer(h._id))
            } else {
                var o = function () {
                    m.currentTime = i, m.muted = h._muted || c._muted || g._muted || m.muted, m.volume = h._volume * g.volume(), m.playbackRate = c._rate, setTimeout(function () {
                        m.play(), c._emit("play", h._id)
                    }, 0)
                };
                if (4 === m.readyState || !m.readyState && navigator.isCocoonJS)o(); else {
                    var p = function () {
                        c._endTimers[h._id] = setTimeout(l, 1e3 * j / Math.abs(c._rate)), o(), m.removeEventListener("canplaythrough", p, !1)
                    };
                    m.addEventListener("canplaythrough", p, !1), c._clearTimer(h._id)
                }
            }
            return h._id
        }, pause: function (a) {
            var b = this;
            if (!b._loaded)return b.once("play", function () {
                b.pause(a)
            }), b;
            for (var c = b._getSoundIds(a), d = 0; d < c.length; d++) {
                b._clearTimer(c[d]);
                var e = b._soundById(c[d]);
                if (e && !e._paused) {
                    if (e._seek = b.seek(c[d]), e._paused = !0, b._webAudio) {
                        if (!e._node.bufferSource)return b;
                        "undefined" == typeof e._node.bufferSource.stop ? e._node.bufferSource.noteOff(0) : e._node.bufferSource.stop(0), e._node.bufferSource = null
                    } else isNaN(e._node.duration) || e._node.pause();
                    arguments[1] || b._emit("pause", e._id)
                }
            }
            return b
        }, stop: function (a) {
            var b = this;
            if (!b._loaded)return "undefined" != typeof b._sounds[0]._sprite && b.once("play", function () {
                b.stop(a)
            }), b;
            for (var c = b._getSoundIds(a), d = 0; d < c.length; d++) {
                b._clearTimer(c[d]);
                var e = b._soundById(c[d]);
                if (e && !e._paused)if (e._seek = e._start || 0, e._paused = !0, e._ended = !0, b._webAudio && e._node) {
                    if (!e._node.bufferSource)return b;
                    "undefined" == typeof e._node.bufferSource.stop ? e._node.bufferSource.noteOff(0) : e._node.bufferSource.stop(0), e._node.bufferSource = null
                } else e._node && !isNaN(e._node.duration) && (e._node.pause(), e._node.currentTime = e._start || 0)
            }
            return b
        }, mute: function (a, c) {
            var d = this;
            if (!d._loaded)return d.once("play", function () {
                d.mute(a, c)
            }), d;
            if ("undefined" == typeof c) {
                if ("boolean" != typeof a)return d._muted;
                d._muted = a
            }
            for (var e = d._getSoundIds(c), f = 0; f < e.length; f++) {
                var h = d._soundById(e[f]);
                h && (h._muted = a, d._webAudio && h._node ? h._node.gain.setValueAtTime(a ? 0 : h._volume * g.volume(), b.currentTime) : h._node && (h._node.muted = g._muted ? !0 : a))
            }
            return d
        }, volume: function () {
            var a, c, d = this, e = arguments;
            if (0 === e.length)return d._volume;
            if (1 === e.length) {
                var f = d._getSoundIds(), h = f.indexOf(e[0]);
                h >= 0 ? c = parseInt(e[0], 10) : a = parseFloat(e[0])
            } else 2 === e.length && (a = parseFloat(e[0]), c = parseInt(e[1], 10));
            var i;
            if (!("undefined" != typeof a && a >= 0 && 1 >= a))return i = c ? d._soundById(c) : d._sounds[0], i ? i._volume : 0;
            if (!d._loaded)return d.once("play", function () {
                d.volume.apply(d, e)
            }), d;
            "undefined" == typeof c && (d._volume = a), c = d._getSoundIds(c);
            for (var j = 0; j < c.length; j++)i = d._soundById(c[j]), i && (i._volume = a, d._webAudio && i._node ? i._node.gain.setValueAtTime(a * g.volume(), b.currentTime) : i._node && (i._node.volume = a * g.volume()));
            return d
        }, fade: function (a, c, d, e) {
            var f = this;
            if (!f._loaded)return f.once("play", function () {
                f.fade(a, c, d, e)
            }), f;
            f.volume(a, e);
            for (var g = f._getSoundIds(e), h = 0; h < g.length; h++) {
                var i = f._soundById(g[h]);
                if (i)if (f._webAudio) {
                    var j = b.currentTime, k = j + d / 1e3;
                    i._volume = a, i._node.gain.setValueAtTime(a, j), i._node.gain.linearRampToValueAtTime(c, k), setTimeout(function (a, d) {
                        setTimeout(function () {
                            d._volume = c, f._emit("faded", a)
                        }, k - b.currentTime > 0 ? Math.ceil(1e3 * (k - b.currentTime)) : 0)
                    }.bind(f, g[h], i), d)
                } else {
                    var l = Math.abs(a - c), m = a > c ? "out" : "in", n = l / .01, o = d / n;
                    !function () {
                        var b = a, d = setInterval(function (a) {
                            b += "in" === m ? .01 : -.01, b = Math.max(0, b), b = Math.min(1, b), b = Math.round(100 * b) / 100, f.volume(b, a), b === c && (clearInterval(d), f._emit("faded", a))
                        }.bind(f, g[h]), o)
                    }()
                }
            }
            return f
        }, loop: function () {
            var a, b, c, d = this, e = arguments;
            if (0 === e.length)return d._loop;
            if (1 === e.length) {
                if ("boolean" != typeof e[0])return c = d._soundById(parseInt(e[0], 10)), c ? c._loop : !1;
                a = e[0], d._loop = a
            } else 2 === e.length && (a = e[0], b = parseInt(e[1], 10));
            for (var f = d._getSoundIds(b), g = 0; g < f.length; g++)c = d._soundById(f[g]), c && (c._loop = a);
            return d
        }, seek: function () {
            var a, c, d = this, e = arguments;
            if (0 === e.length)c = d._sounds[0]._id; else if (1 === e.length) {
                var f = d._getSoundIds(), g = f.indexOf(e[0]);
                g >= 0 ? c = parseInt(e[0], 10) : (c = d._sounds[0]._id, a = parseFloat(e[0]))
            } else 2 === e.length && (a = parseFloat(e[0]), c = parseInt(e[1], 10));
            if ("undefined" == typeof c)return d;
            if (!d._loaded)return d.once("load", function () {
                d.seek.apply(d, e)
            }), d;
            var h = d._soundById(c);
            if (h) {
                if (!(a >= 0))return d._webAudio ? h._seek + (b.currentTime - h._playStart) : h._node.currentTime;
                var i = d.playing(c);
                i && d.pause(c, !0), h._seek = a, d._clearTimer(c), i && d.play(c)
            }
            return d
        }, playing: function (a) {
            var b = this, c = b._soundById(a) || b._sounds[0];
            return c ? !c._paused : !1
        }, duration: function () {
            return this._duration
        }, unload: function () {
            for (var a = this, b = a._sounds, c = 0; c < b.length; c++) {
                b[c]._paused || (a.stop(b[c]._id), a._emit("end", b[c]._id)), a._webAudio || (b[c]._node.src = "", b[c]._node.removeEventListener("error", b[c]._errorFn, !1), b[c]._node.removeEventListener("canplaythrough", b[c]._loadFn, !1)), delete b[c]._node, a._clearTimer(b[c]._id);
                var d = g._howls.indexOf(a);
                d >= 0 && g._howls.splice(d, 1)
            }
            return j && delete j[a._src], a = null, null
        }, on: function (a, b, c) {
            var d = this, e = d["_on" + a];
            return "function" == typeof b && e.push({id: c, fn: b}), d
        }, off: function (a, b, c) {
            var d = this, e = d["_on" + a];
            if (b) {
                for (var f = 0; f < e.length; f++)if (b === e[f].fn && c === e[f].id) {
                    e.splice(f, 1);
                    break
                }
            } else e = [];
            return d
        }, once: function (a, b, c) {
            var d = this, e = function () {
                b.apply(d, arguments), d.off(a, e, c)
            };
            return d.on(a, e, c), d
        }, _emit: function (a, b, c) {
            for (var d = this, e = d["_on" + a], f = 0; f < e.length; f++)e[f].id && e[f].id !== b || setTimeout(function (a) {
                a.call(this, b, c)
            }.bind(d, e[f].fn), 0);
            return d
        }, _clearTimer: function (a) {
            var b = this;
            return b._endTimers[a] && (clearTimeout(b._endTimers[a]), delete b._endTimers[a]), b
        }, _soundById: function (a) {
            for (var b = this, c = 0; c < b._sounds.length; c++)if (a === b._sounds[c]._id)return b._sounds[c];
            return null
        }, _inactiveSound: function () {
            var a = this;
            a._drain();
            for (var b = 0; b < a._sounds.length; b++)if (a._sounds[b]._ended)return a._sounds[b].reset();
            return new i(a)
        }, _drain: function () {
            var a = this, b = a._pool, c = 0, d = 0;
            if (!(a._sounds.length < b)) {
                for (d = 0; d < a._sounds.length; d++)a._sounds[d]._ended && c++;
                for (d = a._sounds.length - 1; d >= 0; d--) {
                    if (b >= c)return;
                    a._sounds[d]._ended && (a._webAudio && a._sounds[d]._node && a._sounds[d]._node.disconnect(0), a._sounds.splice(d, 1), c--)
                }
            }
        }, _getSoundIds: function (a) {
            var b = this;
            if ("undefined" == typeof a) {
                for (var c = [], d = 0; d < b._sounds.length; d++)c.push(b._sounds[d]._id);
                return c
            }
            return [a]
        }, _refreshBuffer: function (a) {
            var c = this;
            return a._node.bufferSource = b.createBufferSource(), a._node.bufferSource.buffer = j[c._src], a._node.bufferSource.connect(a._panner ? a._panner : a._node), a._node.bufferSource.loop = a._loop, a._loop && (a._node.bufferSource.loopStart = a._start || 0, a._node.bufferSource.loopEnd = a._stop), a._node.bufferSource.playbackRate.value = c._rate, c
        }
    };
    var i = function (a) {
        this._parent = a, this.init()
    };
    if (i.prototype = {
            init: function () {
                var a = this, b = a._parent;
                return a._muted = b._muted, a._loop = b._loop, a._volume = b._volume, a._muted = b._muted, a._seek = 0, a._paused = !0, a._ended = !0, a._id = Math.round(Date.now() * Math.random()), b._sounds.push(a), a.create(), a
            }, create: function () {
                var a = this, c = a._parent, d = g._muted || a._muted || a._parent._muted ? 0 : a._volume * g.volume();
                return c._webAudio ? (a._node = "undefined" == typeof b.createGain ? b.createGainNode() : b.createGain(), a._node.gain.setValueAtTime(d, b.currentTime), a._node.paused = !0, a._node.connect(e)) : (a._node = new Audio, a._errorFn = a._errorListener.bind(a), a._node.addEventListener("error", a._errorFn, !1), a._loadFn = a._loadListener.bind(a), a._node.addEventListener("canplaythrough", a._loadFn, !1), a._node.src = c._src, a._node.preload = "auto", a._node.volume = d, a._node.load()), a
            }, reset: function () {
                var a = this, b = a._parent;
                return a._muted = b._muted, a._loop = b._loop, a._volume = b._volume, a._muted = b._muted, a._seek = 0, a._paused = !0, a._ended = !0, a._sprite = null, a._id = Math.round(Date.now() * Math.random()), a
            }, _errorListener: function () {
                var a = this;
                a._node.error && 4 === a._node.error.code && (g.noAudio = !0), a._parent._emit("loaderror", a._id, a._node.error ? a._node.error.code : 0), a._node.removeEventListener("error", a._errorListener, !1)
            }, _loadListener: function () {
                var a = this, b = a._parent;
                b._duration = Math.ceil(10 * a._node.duration) / 10, 0 === Object.keys(b._sprite).length && (b._sprite = {__default: [0, 1e3 * b._duration]}), b._loaded || (b._loaded = !0, b._emit("load")), b._autoplay && b.play(), a._node.removeEventListener("canplaythrough", a._loadListener, !1)
            }
        }, c)var j = {}, k = function (a) {
        var b = a._src;
        if (j[b])return a._duration = j[b].duration, void n(a);
        if (/^data:[^;]+;base64,/.test(b)) {
            window.atob = window.atob || function (a) {
                for (var b, c, d = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", e = String(a).replace(/=+$/, ""), f = 0, g = 0, h = ""; c = e.charAt(g++); ~c && (b = f % 4 ? 64 * b + c : c, f++ % 4) ? h += String.fromCharCode(255 & b >> (-2 * f & 6)) : 0)c = d.indexOf(c);
                return h
            };
            for (var c = atob(b.split(",")[1]), d = new Uint8Array(c.length), e = 0; e < c.length; ++e)d[e] = c.charCodeAt(e);
            m(d.buffer, a)
        } else {
            var f = new XMLHttpRequest;
            f.open("GET", b, !0), f.responseType = "arraybuffer", f.onload = function () {
                m(f.response, a)
            }, f.onerror = function () {
                a._webAudio && (a._html5 = !0, a._webAudio = !1, a._sounds = [], delete j[b], a.load())
            }, l(f)
        }
    }, l = function (a) {
        try {
            a.send()
        } catch (b) {
            a.onerror()
        }
    }, m = function (a, c) {
        b.decodeAudioData(a, function (a) {
            a && (j[c._src] = a, n(c, a))
        }, function () {
            c._emit("loaderror")
        })
    }, n = function (a, b) {
        b && !a._duration && (a._duration = b.duration), 0 === Object.keys(a._sprite).length && (a._sprite = {__default: [0, 1e3 * a._duration]}), a._loaded || (a._loaded = !0, a._emit("load")), a._autoplay && a.play()
    };
    "function" == typeof define && define.amd && define("howler", function () {
        return {Howler: g, Howl: h}
    }), "undefined" != typeof exports && (exports.Howler = g, exports.Howl = h), "undefined" != typeof window && (window.HowlerGlobal = f, window.Howler = g, window.Howl = h, window.Sound = i)
}(), function (a) {
	
	
    "use strict";
    function b(a) {
        return Math.round(100 * a) / 100
    }

    function c(a, b) {
        return Math.floor(Math.random() * (a - b) + b)
    }

    function d(a, b) {
        return Math.random() * a + b
    }

    function e(a, b) {
        return Math.random() * (b - a) + a
    }

    function f(a) {
        return a[Math.floor(Math.random() * a.length)]
    }

    function g() {
        return "#" + Math.floor(16777215 * Math.random()).toString(16)
    }

    function h(a, b) {
        return a > b ? a : b
    }

    function i(a, b) {
        return b > a ? a : b
    }

    function j(a, b, c) {
        return a > c ? a : c > b ? b : c
    }

    function k() {
        return 2 * Math.random() - 1 + (2 * Math.random() - 1) + (2 * Math.random() - 1)
    }

    function l(a, b) {
        return k() * b + a
    }

    function m(a, b, c, d) {
        var e = a - c, f = b - d;
        return Math.sqrt(Math.pow(e, 2) + Math.pow(f, 2))
    }

    function n(a) {
        var b = {}, c = a.substring(1).split("&");
        if (c.length) {
            for (var d = 0; d < c.length; d++) {
                var e = c[d].split("=");
                b[decodeURI(e[0])] = decodeURI(e[1])
            }
            return b
        }
    }

    function o(a) {
        a = a.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var b = new RegExp("[\\?&]" + a + "=([^&#]*)"), c = b.exec(location.search);
        return null === c ? "" : decodeURIComponent(c[1].replace(/\+/g, " "))
    }

    function p() {
        var a = $(".qr-code-overlay");
        $(".sharing-icon.wechat").on("click", function (b) {
            b.preventDefault(), a.hasClass("active") || a.addClass("active")
        }), $(document).on("click", ".bd_weixin_popup_close", function () {
            a.hasClass("active") && a.removeClass("active")
        })
    }

    var q = {
        mobile: a.Modernizr.touch,
        iOS: /(iPad|iPhone|iPod)/g.test(navigator.userAgent),
        wechat: /MicroMessenger/g.test(navigator.userAgent),
        clickEvent: a.Modernizr.touch ? "touchstart" : "click",
        mousedownEvent: a.Modernizr.touch ? "touchstart" : "mousedown",
        mousemoveEvent: a.Modernizr.touch ? "touchmove" : "mousemove",
        mouseupEvent: a.Modernizr.touch ? "touchend" : "mouseup"
    };
    window.requestAnimFrame = function () {
        return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function (a) {
                window.setTimeout(a, 1e3 / 60)
            }
    }();
    var r = function (a, b) {
        if ("number" == typeof a && "number" == typeof b)this.x = a, this.y = b; else {
            if (!(a instanceof r && 1 === arguments.length))throw new Error("Invalid arguments" + a + "," + b);
            this.x = a.x, this.y = a.y
        }
    };
    r.prototype.sub = function (a) {
        return new r(this.x - a.x, this.y - a.y)
    }, r.prototype.add = function (a) {
        return new r(this.x + a.x, this.y + a.y)
    }, r.prototype.distance = function (a) {
        var b = this.x - a.x, c = this.y - a.y;
        return Math.sqrt(b * b + c * c)
    }, r.prototype.multNew = function (a) {
        return new r(this.x * a, this.y * a)
    }, r.prototype.magnitude = function () {
        return Math.sqrt(this.x * this.x + this.y * this.y)
    }, r.prototype.round = function () {
        this.x = Math.round(10 * this.x) / 10, this.y = Math.round(10 * this.y) / 10
    }, r.prototype.toString = function () {
        return "x:" + this.x + ",y:" + this.y + "]"
    };
    var s = {
        roundTwo: b,
        randomCalc: c,
        randomCalc2: d,
        random: e,
        rnd: l,
        randomColor: g,
        randomFromList: f,
        minVal: h,
        maxVal: i,
        betweenVal: j,
        calculateDistance: m,
        extractParameters: n,
        getUrlParameterByName: o,
        showQRcodeOverlay: p,
        Vector: r,
        noop: function () {
        }
    };
 
    a.client = q, a.budCNY = {
        util: s,
        client: q,
        parametersUrl: n(window.location.search),
        parametersFromHash: n(window.location.hash),
        config: {
            fpsMeter: !1,
            gatewayEndPoint: add_zz,
			//gatewayEndPoint:"./index.php?i=7&j=8&c=entry&rid=193&do=wish&m=dream",
            fontServerUrl: "http://bud-font-947736416.ap-northeast-1.elb.amazonaws.com/api/font/",
            wechat: {
                appId: "",
                imgUrl: $(".sharing-poster").attr("src") ? window.location.origin + "/" + $(".sharing-poster").attr("src") : null,
                friendDesc: "想要一生一次登上纽约时代广场大屏幕? 现在就许个最闪新年梦，让全世界为你的梦想举杯！",
                friendTitle: "我刚许的新年梦，将高调登上纽约时代广场！快来观望",
                momentsDesc: "",
                momentsTitle: "我刚许的新年梦，将高调登上纽约时代广场！快来观望",
                genericTitle: "我刚许的新年梦，将高调登上纽约时代广场！快来观望"
            }
        }
    }
}(window), function () {
    "use strict";
    function a() {
        this.selector = !1, this.count = 0, a.prototype.init = function (a, b) {
            this.selector = a, this.style = document.createElement("style"), this.style.setAttribute("type", "text/css"), document.getElementsByTagName("head")[0].appendChild(this.style), this.serverUrl = b
        }, a.prototype.createDynamicFont = function (a, b, c, d, e, f, g) {
            var h = c.join() + "BESbswy";
            this.style.innerHTML += '@font-face {font-family:"' + a + '";src: url("' + this.serverUrl + b + ".ttf?text=" + window.encodeURIComponent(h) + '") format("truetype");};\n', window.WebFont.load({
                custom: {
                    families: [a],
                    testStrings: {fontFamily: h}
                }, active: d, inactive: e, fontactive: f, fontinactive: g, timeout: 1e3
            })
        }
    }

    window.budCNY.FontManager = a
}(window), function () {
    "use strict";
    function a(a) {
        return a.bdUrl = window.budCNY.config.sharingUrl, a
    }

    window._bd_share_config = {
        share: [{
            tag: "share_1",
            bdText: "想要一生一次登上纽约时代广场大屏幕？现在就许个最闪新年梦，让全世界为你的梦想举杯！ #百威为梦想举杯#",
            bdDesc: "想要一生一次登上纽约时代广场大屏幕？现在就许个最闪新年梦，让全世界为你的梦想举杯！ #百威为梦想举杯#",
            bdPic: window.budCNY.config.wechat.imgUrl,
            bdSize: 32
        }, {
            tag: "share_2",
            bdText: "想要一生一次登上纽约时代广场大屏幕？现在就许个最闪新年梦，让全世界为你的梦想举杯！ #百威为梦想举杯#",
            bdDesc: "想要一生一次登上纽约时代广场大屏幕？现在就许个最闪新年梦，让全世界为你的梦想举杯！ #百威为梦想举杯#",
            bdPic: window.budCNY.config.wechat.imgUrl,
            onBeforeClick: function (b, c) {
                return a(c), c
            },
            bdSize: 32
        }]
    };
     var b = window._bd_share_config.share.reduce(function (a, b) {
        return a[b.tag] = b, a
    }, {});
    $.ajax({url: "http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion", dataType: "script", cache: !1});
    var c = $(".sharing-icon.qzone");
    c.attr("title", "分享到QQ空间"), c.on("click", function (a) {
        a.preventDefault();
        var c = this, d = b[c.parentElement.dataset.tag] || {};
        d.onBeforeClick && (d = d.onBeforeClick("qzone", d)), d.bdSite = d.bdUrl || window.location.href, d.bdText = "百威为梦想举杯";
        var e = {url: d.bdSite, title: d.bdText, summary: d.bdDesc, site: d.bdSite, pics: d.bdPic}, f = [];
        for (var g in e)f.push(g + "=" + window.encodeURIComponent(e[g] || ""));
        var h = "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?";
        window.budCNY.client.mobile && (h = "http://openmobile.qq.com/oauth2.0/m_jump?page=qzshare.html&loginpage=loginindex.html&logintype=qzone&"), window.open(h + f.join("&"))
    })
}(window), function (a) {
    "use strict";
    function b(a, b) {
        for (var c, d = k.length, e = 0; d > e; e++)c = k[e], c[a] && c[a](b)
    }

    function c(a) {
        switch (a.err_msg) {
            case"send_app_msg:cancel":
                b("wechat.menu.share.appmessage", ["cancel"]);
                break;
            case"send_app_msg:ok":
            case"send_app_msg:confirm":
                b("wechat.menu.share.appmessage", ["ok"])
        }
    }

    function d(a) {
        switch (a.err_msg) {
            case"share_timeline:cancel":
                b("wechat.menu.share.timeline", ["cancel"]);
                break;
            case"share_timeline:ok":
            case"share_timeline:confirm":
                b("wechat.menu.share.timeline", ["ok"])
        }
    }

    function e(a) {
        switch (a.err_msg) {
            case"share_weibo:cancel":
                b("wechat.menu.share.weibo", ["cancel"]);
                break;
            case"share_weibo:ok":
            case"share_weibo:confirm":
                b("wechat.menu.share.weibo", ["ok"])
        }
    } 

    function f() {
        window.WeixinJSBridge.on("menu:share:appmessage", function () {
            var a = j.link || window.location.href.replace(/(&|\?)fromSharing=[^&]*/, "");
            window.WeixinJSBridge.invoke("sendAppMessage", {
                appid: j.appId,
                img_url: j.imgUrl,
                img_width: "120",
                img_height: "120",
                link: a,
                desc: j.friendDesc,
                title: j.friendTitle
            }, c), b("wechat.menu.share.appmessage", ["click", "url=" + a])
        }), window.WeixinJSBridge.on("menu:share:timeline", function () {
            var a = j.link || window.location.href.replace(/(&|\?)fromSharing=[^&]*/, "");
            window.WeixinJSBridge.invoke("shareTimeline", {
                img_url: j.imgUrl,
                img_width: "120",
                img_height: "120",
                link: a,
                desc: j.momentsDesc,
                title: j.momentsTitle
            }, d), b("wechat.menu.share.timeline", ["click", "url=" + a])
        }), window.WeixinJSBridge.on("menu:share:weibo", function () {
            var a = j.link || window.location.href.replace(/(&|\?)fromSharing=[^&]*/, "");
            window.WeixinJSBridge.invoke("shareWeibo", {
                content: j.friendTitle,
                url: a
            }, e), b("wechat.menu.share.weibo", ["click", "url=" + a])
        })
    }

    function g() {
        window.budCNY.client.wechat = !0, b("bridgeReady"), f()
    }

    function h(a) {
        k.push(a)
    }

    function i(a) {
        var b = k.indexOf(a);
        -1 !== b && k.splice(b, 1)
    }

    var j = a.budCNY.config.wechat, k = [];
    "undefined" == typeof WeixinJSBridge ? document.addEventListener ? document.addEventListener("WeixinJSBridgeReady", g, !1) : document.attachEvent && (document.attachEvent("WeixinJSBridgeReady", g), document.attachEvent("onWeixinJSBridgeReady", g)) : g(), window.budCNY.wechat = {
        registerListener: h,
        unregisterListener: i
    }
}(window), function (a) {
    "use strict";
    function b() {
        a._gaq = a._gaq || [], a._gaq.push(["_setAccount", "UA-52397263-1"]), a._gaq.push(["_trackPageview"])
    }

    function c() {
        a._CiQ10406 = window._CiQ10406 || []
    }

    function d() {
        b(), c(), e()
    }

    function e() {
        $("body").on(window.budCNY.client.clickEvent || "click", "[data-analytics]", function () {
            var a = $(this).attr("data-analytics");
            void 0 !== a && h(a, ["clicked"])
        })
    }

    function f(b, c) {
        var d = ["_trackEvent", b].concat(c);
        a._gaq.push(d)
    }

    function g(b, c) {
        Array.isArray(c) || (c = [c]);
        var d = b + "." + c.join(".");
        a._CiQ10406.push(["_trackEvent", {
            type: 1,
            labels: [{"按钮名称": d}],
            values: [{"数量": 1}]
        }]), a.CClickiV3 && a.CClickiV3[10406] && a.CClickiV3[10406]._flushObserver && a.CClickiV3[10406]._flushObserver(a.budCNY.util.noop)
    }

    function h(a, b) {
        f(a, b), g(a, b)
    }

    function i() {
        d()
    }

    i.prototype = {sendEvent: h}, a.budCNY.Analytics = i
}(window), function () {
    "use strict";
    function a(a) {
        window.location.hash = "dreamId=" + a.uuid
    }

    function b() {
        document.title = window.budCNY.config.wechat.genericTitle
    }

    function c() {
        this.wrapperContainer = $(".wrapper-container"), this.spriteInterval = !1, this.mobileWechatSharing = $(".mobile-wechat-sharing"), this.mobileDefaultSharing = $(".mobile-default-sharing")
    }

    function d() {
        this.actionsEnd = $(".actions"), this.loader = $(".loader"), this.startReceiveButton = $(".start-receive"), this.rotateDiv = $(".rotate-device"), this.experienceMode = !1, this.spriteAnim = $(".sprite-anim"), this.motionCanvas = $("#motionCanvas"), this.supportedMotion = !1, this.logo = $(".top-informations").find(".logo"), this.sound = $(".sound"), this.bottle = $(".bottle"), this.bgStars = $(".stars-wrapper")
    }

    function e() {
        this.inputFirst = $(".input-text").eq(0), this.inputSecond = $(".input-text").eq(1), this.inputThird = $(".input-text").eq(2), this.counter = $(".counter"), this.inputFirst.val(""), this.inputSecond.val(""), this.inputThird.val(""), this.toastFrom = $(".toast-from"), this.toastTo = $(".toast-to"), this.toastInformations = $(".toast-informations"), this.copyLink = $("#copy-url-content"), this.mobileCopyLink = $("#mobile-default-sharing-link"), this.launchLink = $(".launch-link"), this.overlayLink = $(".overlay-link"), this.finalMessage = $(".final-message"), this.flagMin = !1;
        var a = function () {
            var a, b;
            window.getSelection && document.createRange ? (b = window.getSelection(), a = document.createRange(), a.selectNodeContents(this), b.removeAllRanges(), b.addRange(a)) : document.selection && document.body.createTextRange && (a = document.body.createTextRange(), a.moveToElementText(this), a.select())
        };
        this.copyLink.on("mouseup", a), this.copyLink.on("copy", function () {
            Ab.sendEvent("dreamentry.share.copypaste", "copy")
        }), this.mobileCopyLink.on("mouseup", a), this.mobileCopyLink.on("copy", function () {
            Ab.sendEvent("dreamentry.share.wechat.copypaste", "copy")
        })
    }

    function f() {
        function a() {
            d.bgId && (document[b] ? d.sprite.pause() : d.sprite.play())
        }

        this.sprite = new window.Howl({
            src: ["../media/sprite_02.m4a", "../media/sprite_02.ogg", "../media/sprite_02.mp3"],
            sprite: {
                blank: [0, 800],
                bg: [1085, 13080, !0],
                "firework-short1": [15e3, 1516.167800453516],
                "firework-short2": [17e3, 889.2290249433117],
                "explosion-step1-1": [19e3, 1124.3310657596376],
                "explosion-step1-2": [21e3, 1307.1882086167789],
                "explosion-step2-1": [23e3, 2456.5759637188194],
                "explosion-step2-2": [26e3, 2796.1678004535174]
            }
        }), this.bgId = !1;
        var b, c, d = this;
        "undefined" != typeof document.hidden ? (b = "hidden", c = "visibilitychange") : "undefined" != typeof document.mozHidden ? (b = "mozHidden", c = "mozvisibilitychange") : "undefined" != typeof document.msHidden ? (b = "msHidden", c = "msvisibilitychange") : "undefined" != typeof document.webkitHidden && (b = "webkitHidden", c = "webkitvisibilitychange"), "undefined" == typeof document.addEventListener || "undefined" == typeof document[b] || document.addEventListener(c, a, !1);
        var e = function () {
            d.sprite.play("blank"), d.sprite.play("blank"), d.sprite.play("blank"), d.sprite.play("blank"), document.removeEventListener("touchstart", e)
        };
        window.Howler.usingWebAudio || document.addEventListener("touchstart", e)
    }

    function g(a, b, c, d, e, f) {
        for (var g, h, i, j = new H(0, 0), k = [], l = 0; b > l; l++)g = Math.random() * Math.PI * 2, h = 15, i = Math.random() < e ? N : O, k.push(new I(a, new H(c * Math.cos(g) * window.budCNY.util.rnd(1, .15), c * Math.sin(g) * window.budCNY.util.rnd(1, .15)), j, h, d, i, f));
        return k
    }

    function h(a, b, c, d, e, f, g, h, i, j) {
        this.z = j, b.x = a.x + (b.x - a.x) / this.z, b.y = a.y + (b.y - a.y) / this.z, c /= this.z, this.character = new P(b, e, d, g, h, i), this.position = a, this.translateVector = new H(this.position.x * (1 - this.z), this.position.y * (1 - this.z)), this.rocket = new Q(a, b, c, 5 / this.z, f)
    }

    function i() {
        this.particles = [], this.nbParticle = 35, this.frame = 0, this.gradient = !1;
        var a = !1, b = 0;
        i.prototype.init = function () {
            for (var a = 0; a < this.nbParticle; a++)this.particles.push(new j);
            _ = ($(".final-message").width() - $(".final-message").find("span").width()) / 2, this.animationFinished = !1, k()
        }, i.prototype.restartParticles = function (a) {
            var b = Math.random() < .5 ? 1 : -1;
            this.particles[a].alpha = .8, this.particles[a].color = "rgba(255, 255, 255, " + this.particles[a].alpha + ")", this.particles[a].vy = window.budCNY.util.randomCalc2(.5, .2) * b, this.particles[a].x = window.budCNY.util.randomCalc(Y - _ + 20, _ - 20), this.particles[a].y = Z / 2
        }, i.prototype.reduceAlpha = function (a) {
            this.particles[a].alpha -= .05, this.particles[a].color = "rgba(255, 255, 255, " + this.particles[a].alpha + ")"
        }, i.prototype.draw = function () {
            for (X.clearRect(0, 0, Y, Z), X.globalCompositeOperation = "lighter", b = 0; b < this.nbParticle; b++)X.beginPath(), a = this.particles[b], this.frame++, a.x = a.x + Math.sin(this.frame / 750 + a.movingX) / 2, a.radius = a.radius + Math.sin(this.frame / 25) / 3, a.vy *= 1.01, a.y += a.vy, (a.y < 0 || a.y > Z) && this.restartParticles(b), (a.y < 20 || a.y > Z - 20) && a.alpha > 0 && this.reduceAlpha(b), X.fillStyle = a.color, X.arc(a.x, a.y, a.radius, 2 * Math.PI, !1), X.fill()
        }
    }

    function j() {
        this.x = window.budCNY.util.randomCalc(Y - _ + 35, _ - 35), this.y = Z / 2;
        var a = Math.random() < .5 ? 1 : -1;
        this.vy = window.budCNY.util.randomCalc2(.5, .2) * a, this.movingX = window.budCNY.util.randomCalc(150, 1), this.alpha = .8, this.color = "rgba(255, 255, 255, " + this.alpha + ")", this.radius = window.budCNY.util.randomCalc2(.8, .5)
    }

    function k() {
        ab.draw(), ab.animationFinished || window.requestAnimFrame(k)
    }

    function l(a, b, c, d) {
        function e(a) {
            for (var b, c = [], d = 1; a >= d; d++)b = document.createElement("canvas"), b.width = T, b.height = U, c.push({
                ctx: b.getContext("2d"),
                opacity: d / a
            });
            return c
        }

        function f(a) {
            for (var b = a.length, c = b, d = a[b - 1].opacity; c-- > 1;)a[c].opacity = a[c - 1].opacity;
            a[0].opacity = d
        }

        function g(a) {
            var c = a.length;
            b.save();
            for (var d = 1; c >= d; d++)b.globalAlpha = a[d - 1].opacity, b.drawImage(a[d - 1].ctx.canvas, 0, 0);
            b.restore()
        }

        this.renderFunction = a, this.buffer = e(c);
        var h, i = 0, j = this;
        this.render = function () {
            j.running && window.requestAnimFrame(j.render), b.clearRect(0, 0, T, U), h = j.buffer[i].ctx, h.fillStyle = "white", h.clearRect(0, 0, T, U), j.renderFunction(h, b), i++, i >= j.buffer.length && (i = 0), f(j.buffer, i), g(j.buffer), d && d()
        }
    }

    function m(a) {
        function b(a, b) {
            for (var c, d = i.length, e = 0; d > e; e++)c = i[e], c[a] && c[a](b)
        }

        function c(a, b, c, d) {
            return new H(window.budCNY.util.random(a, b), window.budCNY.util.random(c, d))
        }

        function d(a, b) {
            var c;
            switch (a) {
                case db:
                    c = new J(80, b, 40, 5, 40, 4, 5, "lighter", 150);
                    break;
                case eb:
                    c = new J(60, b, 20, 3, 40, 4, 5, "source-over", 100);
                    break;
                case fb:
                    c = new J(60, b, 15, 1, 40, 4, 2, "source-over", 60);
                    break;
                default:
                    c = new J(20, b, 10, 1, 40, 0, 1, "source-over", 40)
            }
            return c
        }

        this.bgStars = $(".stars-wrapper"), this.clouds = $(".clouds"), this.bottle = $(".bottle"), this.moon = $(".moon"), this.finalMessage = $(".final-message"), this.countFireworkEnded = 0, this.countLaunchedFirework = 0, this.fireworkReady = !1, this.fireworkFlag = !0, this.width = window.innerWidth, this.height = window.innerHeight;
        var e = document.createElement("canvas");
        e.width = 200, e.height = 200;
        var f = e.getContext("2d"), i = [];
        m.prototype.registerListener = function (a) {
            i.push(a)
        }, m.prototype.unregisterListener = function (a) {
            var b = i.indexOf(a);
            -1 !== b && i.splice(b, 1)
        };
        var j, k = this, n = [], o = [];
        m.prototype.addExplosions = function () {
            if (n.length < 2) {
                var a = c(.1 * k.width, .9 * k.width, k.height / 4, k.height / 2);
                n.push(new M(a, g(a, p.finalExplosionsParticleCount, p.explosionParticleVelocityMagnitude, window.budCNY.util.rnd(C / 3, C / 16), Math.random(), A), window.budCNY.util.noop, window.budCNY.util.rnd(.5, .1)))
            }
        };
        var p, q, r;
        m.prototype.createFireworks = function (a) {
            j = $(".input-text").filter(".dream").val().toUpperCase(), this.width = window.innerWidth, this.height = window.innerHeight, p = d(a, this.width), S.globalCompositeOperation = p.compositeOperation;
            var b = function () {
                bb && bb.tick()
            };
            q = new l(function (a, b) {
                for (var c = o.length; c--;)o[c] && (o[c].draw(a, b), o[c].update(), k.fireworkUpdate())
            }, S, p.mainExplosionsBufferSize, b), r = new l(function (a, b) {
                for (var c = n.length; c--;)n[c] && (n[c].draw(a, b), n[c].isAlive() || n.splice(c, 1));
                k.addExplosions()
            }, S, p.finalExplosionsBufferSize, b);
            var e = function () {
                function a(a) {
                    return new h(new H(k.width / 2, k.height), new H(k.width / 2, k.height / 3), k.height / 350, f, a, x.throwLongFirework, x.explode11, x.explode22, p, 1)
                }

                var b, d = 0;
                for (d = 0; d < j.length; d++)try {
                    b = a(j.charAt(d)), o.push(b);
                    break
                } catch (e) {
                }
                for (var g = d + 1; g < j.length; g++) {
                    var i = window.budCNY.util.random(.7, 1), l = c(.1 * k.width, .9 * k.width, k.height / 4, k.height / 2);
                    try {
                        b = new h(new H(l.x, k.height), l, k.height / 125, f, j.charAt(g), x.throwShortFirework2, x.explode12, x.explode21, p, i), o.push(b)
                    } catch (e) {
                    }
                }
                o.length < 1 && o.push(a("*")), hb.fireworkReady = !0
            }, g = new window.budCNY.FontManager;
            g.init(!1, window.budCNY.config.fontServerUrl), g.createDynamicFont("fireworkFont", "Lantinghei%20SC%20Demibold", [j], e, e)
        }, m.prototype.throwMainFirework = function (a) {
            q.start(), this.lastFiredFirework = o[0], o[0].launch(), u.to(".toast-informations.active", 2, {
                alpha: 1,
                ease: "easeIn",
                delay: .4
            }), b("fireworkStart", a)
        }, m.prototype.moveUp = function () {
            var a = this;
            u.to(this.bottle, 1.2, {
                yPercent: 100, ease: "easeIn", force3D: !0, onComplete: function () {
                    a.bottle.addClass("ended")
                }
            }), u.to(this.clouds, 5, {
                yPercent: 550,
                ease: "Cubic.easeOut",
                force3D: !0
            }), u.to(this.bgStars, 5, {yPercent: 34, ease: "Cubic.easeOut", force3D: !0})
        }, m.prototype.replayFirework = function () {
            r.stop(), n.length = 0, setTimeout(function () {
                S.clearRect(0, 0, T, U)
            }, 100), u.to(".toast-informations.active", 1, {
                alpha: 0,
                ease: "easeIn"
            }), u.to(".share-container", .8, {alpha: 0, ease: "easeIn"});
            for (var a = o.length; a--;)o[a].reset();
            this.countLaunchedFirework = 0, this.countFireworkEnded = 0, this.moveDown()
        }, m.prototype.moveDown = function () {
            var b = this;
            this.bottle.removeClass("ended"), u.to(this.finalMessage, 1.4, {
                yPercent: 0,
                ease: "Cubic.easeOut",
                force3D: !0
            }), u.to(this.moon, 1.8, {y: 0, ease: "Cubic.easeOut", force3D: !0}), u.to(this.bottle, 1.8, {
                yPercent: 0,
                ease: "Cubic.easeOut",
                force3D: !0
            }), u.to(this.clouds, 1.8, {
                yPercent: 0,
                ease: "Cubic.easeOut",
                force3D: !0
            }), u.to(this.bgStars, 2, {
                yPercent: 0, ease: "Cubic.easeOut", force3D: !0, onComplete: function () {
                    u.set(b.clouds, {y: 0}), a.goToSection("sending" === b.experienceMode ? ".toast-container.sending" : ".toast-container.receiving"), hb.fireworkFlag = !0, setTimeout(function () {
                        pb ? a.spriteAnim(".toast-container.sending .sprite-anim.touch", 200, 58) : a.spriteAnim(".toast-container.sending .sprite-anim.toast", 220, 41)
                    }, 500)
                }
            })
        }, m.prototype.displayFinalMessage = function () {
            var b = this;
            ab.init(), u.to(this.finalMessage, 3, {
                yPercent: 250,
                ease: "Cubic.easeInOut",
                force3D: !0
            }), u.to(this.clouds, 3, {y: 275, ease: "Cubic.easeInOut", force3D: !0}), u.to(this.moon, 3, {
                y: -175,
                ease: "Cubic.easeInOut",
                force3D: !0
            }), u.to(this.bgStars, 3, {
                yPercent: 40, ease: "Cubic.easeInOut", force3D: !0, onComplete: function () {
                    a.goToSection(".share-container"), $(".action-wrapper").addClass("active"), u.to(".action-wrapper", .6, {
                        alpha: 1,
                        ease: "easeIn",
                        delay: .4
                    }), b.addExplosions(), r.start()
                }
            })
        }, m.prototype.fireworkEnd = function () {
            this.countFireworkEnded++, this.countFireworkEnded === j.length && (q.stop(), this.displayFinalMessage(), b("fireworkEnd"))
        }, m.prototype.fireworkUpdate = function () {
            this.lastFiredFirework && (this.lastFiredFirework.isExploded() && ++this.countLaunchedFirework < o.length && (this.lastFiredFirework = o[this.countLaunchedFirework], this.lastFiredFirework.launch()), this.lastFiredFirework.isLaunched() && !this.lastFiredFirework.isAlive() && this.fireworkEnd())
        }
    }

    function n() {
        this.desktopSlowest = 30, this.goodMobileSlowest = 60, this.slowMobileSlowest = 100
    }

    function o(a) {
        if (!a.desktopSlowest || !a.goodMobileSlowest || !a.slowMobileSlowest)throw new Error("testHeuristics doesnt define all properties");
        this.testHeuristics = a, this.currentIntervalStart = null, this.intervals = [];
        var b = function () {
            return Date.now()
        };
        o.prototype.start = function () {
            this.start = b(), this.currentIntervalStart = this.start
        }, o.prototype.lap = function () {
            if (null === this.currentIntervalStart)throw new Error("Lap called before Start");
            var a = b();
            this.intervals.push(a - this.currentIntervalStart), this.currentIntervalStart = a
        }, o.prototype.stop = function () {
            {
                var a = {};
                Math.floor(.95 * this.intervals.length)
            }
            return a.mean = (b() - this.start) / this.intervals.length, a.samplesCount = this.intervals.length, this.categorize(a.mean)
        }, o.prototype.categorize = function (a) {
            var b = this.testHeuristics, c = fb;
            return a < b.desktopSlowest ? c = db : a < b.goodMobileSlowest ? c = eb : a > b.slowMobileSlowest && (c = gb), c
        }
    }

    function p(a, b) {
        function c() {
            var a = new H(Math.random() * b.canvas.width, Math.random() * b.canvas.height);
            d.push(new M(a, g(a, 40, .01, C / 3, Math.random(), A), window.budCNY.util.noop, 1))
        }

        this.bench = a, this.ctx = b;
        var d = [], e = this;
        p.prototype.start = function (a) {
            {
                var b = 500;
                new Date
            }
            this.ctx.save(), this.ctx.globalCompositeOperation = "lighter";
            for (var f = 8; f > 0; f--)c();
            var g = new l(function (a, b) {
                for (var c = d.length; c--;)d[c] && d[c].draw(a, b)
            }, this.ctx, 5, function () {
                e.bench.lap()
            });
            this.bench.start(), g.start(), setTimeout(function () {
                g.stop();
                var b = e.bench.stop();
                d.length = 0, e.ctx = null, a(b)
            }, b)
        }
    }

    function q() {
        if (window.requestAnimFrame(q), jb.clearRect(0, 0, kb, lb), mb.length > 0) {
            jb.beginPath(), jb.moveTo(mb[0].x, mb[0].y);
            for (var a = 1; a < mb.length; a++)jb.lineTo(mb[a].x, mb[a].y);
            jb.stroke()
        }
    }

    function r() {
        var a = S.globalCompositeOperation;
        T = window.innerWidth, U = window.innerHeight, R.width = T, R.height = U, S.globalCompositeOperation = a
    }

    function s() {
        r(), kb = $(".toast-container").width(), lb = $(".toast-container").height(), ib.width = kb, ib.height = lb, Y = $(".final-message").width(), Z = $(".final-message").height(), W.width = Y, W.height = Z, _ = ($(".final-message").width() - $(".final-message").find("span").width()) / 2
    }

    function t() {
        pb = !0, ob.spriteAnim.filter(".touch").addClass("active"), ob.motionCanvas.addClass("active"), $(".sprite-container").addClass("touch"), $(window).on(window.budCNY.client.mousedownEvent, function (a) {
            nb && (window.budCNY.client.mobile ? (ub = a.originalEvent.touches[0].pageY, tb = a.originalEvent.touches[0].pageX) : (xb = !0, ub = a.pageY, tb = a.pageX))
        }), $(window).on(window.budCNY.client.mousemoveEvent, function (a) {
            nb && (window.budCNY.client.mobile ? (wb = ub - a.originalEvent.touches[0].pageY, vb = tb - a.originalEvent.touches[0].pageX, mb.length < 100 && mb.push({
                x: a.originalEvent.touches[0].pageX,
                y: a.originalEvent.touches[0].pageY
            })) : xb && (a.preventDefault(), wb = ub - a.pageY, vb = tb - a.pageX, mb.push({
                x: a.pageX - (T - kb) / 2,
                y: a.pageY
            }), mb.length > 100 && (xb = !1)))
        }), $(window).on(window.budCNY.client.mouseupEvent, function () {
            nb && (window.budCNY.client.mobile || (xb = !1), hb.fireworkReady && hb.fireworkFlag && wb > 70 && (hb.fireworkFlag = !1, clearInterval(w.spriteInterval), w.hideInstructions(".toast-container." + ob.experienceMode), hb.throwMainFirework("noaccelerometer"), hb.moveUp())), ub = 0, tb = 0, mb = []
        })
    }

    !function () {
        var a = function () {
        };
        window.console || (window.console = {log: a, info: a, warn: a, debug: a, error: a})
    }();
    var u = window.TweenMax, v = window.budCNY.wechat;
    c.prototype.hideInstructions = function (a) {
        u.to(a, .2, {alpha: 0, ease: "easeIn"})
    }, c.prototype.showWechatOverlay = function () {
        this.mobileWechatSharing.addClass("active"), u.to(this.mobileWechatSharing, .6, {alpha: 1, ease: "easeIn"})
    }, c.prototype.hideWechatOverlay = function () {
        var a = this;
        u.to(this.mobileWechatSharing, .2, {
            alpha: 0, ease: "easeIn", onComplete: function () {
                a.mobileWechatSharing.removeClass("active")
            }
        })
    }, c.prototype.showDefaultOverlay = function () {
        this.mobileDefaultSharing.addClass("active"), u.to(this.mobileDefaultSharing, .6, {alpha: 1, ease: "easeIn"})
    }, c.prototype.hideDefaultOverlay = function () {
        var a = this;
        u.to(this.mobileDefaultSharing, .2, {
            alpha: 0, ease: "easeIn", onComplete: function () {
                a.mobileDefaultSharing.removeClass("active")
            }
        })
    }, c.prototype.goToSectionFirst = function (a) {
        $(a).addClass("active"), u.to(a, .6, {alpha: 1, ease: "easeIn"})
    }, c.prototype.goToSection = function (a) {
        var b = this, c = this.wrapperContainer.filter(".active");
        u.to(c, .4, {
            alpha: 0, ease: "easeIn", onComplete: function () {
                b.wrapperContainer.removeClass("active"), $(a).addClass("active"), u.to(a, .6, {
                    alpha: 1,
                    ease: "easeIn"
                })
            }
        })
    }, c.prototype.spriteAnim = function (a, b, c) {
        var d = 0, e = 0;
        this.spriteInterval = setInterval(function () {
            d = -b * e, e++, u.set(a, {y: d, force3D: !0}), e === c && (e = 1)
        }, 30)
    };
    var w = new c;
    d.prototype.init = function () {
        var a = function () {
            $(".mail-action").addClass("hide"), $(".wechat-action").removeClass("hide"), v.unregisterListener(b)
        }, b = {bridgeReady: a};
        v.registerListener(b), window.budCNY.client.wechat && a(), this.intro()
    }, d.prototype.intro = function () {
        var a = this;
        u.to(this.sound, 1, {
            alpha: 1, force3D: !0, ease: "easeIn", delay: .2, onComplete: function () {
                u.to(a.logo, .8, {
                    yPercent: -35,
                    ease: "Expo.easeInOut",
                    force3D: !0,
                    delay: 1.4
                }), u.to(a.sound, .4, {alpha: 0, force3D: !0, ease: "easeIn", delay: 1}), u.to(a.bgStars, 2, {
                    y: -50,
                    ease: "Expo.easeInOut",
                    force3D: !0,
                    delay: .8
                }), u.to(a.bottle, 2, {
                    yPercent: -57,
                    clearProps: "all",
                    ease: "Expo.easeInOut",
                    force3D: !0,
                    delay: .8,
                    onComplete: function () {
                        a.bottle.addClass("first-ended"), a.start()
                    }
                })
            }
        })
    }, d.prototype.start = function () {
        this.checkUrl();
        var a = document.createElement("canvas");
        a.width = T, a.height = U;
        var b = a.getContext("2d"), c = new p(new o(new n), b);
        c.start(function (a) {
            cb = a
        })
    }, d.prototype.checkUrl = function () {
        this.actionsEnd.removeClass("active"), this.actionsEnd.filter(".replay, .home").addClass("active");
        var a = window.budCNY.parametersUrl.dreamId || window.budCNY.parametersFromHash.dreamId;
        a ? (this.experienceMode = "receiving", hb.experienceMode = "receiving", w.goToSectionFirst(".toast-container.receiving"), u.to(".bud-title", .2, {
            alpha: 0,
            ease: "easeIn"
        }), this.getMessage(a), this.actionsEnd.filter(".redo").addClass("active")) : (this.experienceMode = "sending", hb.experienceMode = "sending", w.goToSectionFirst(".input-container"), this.actionsEnd.filter(".create").addClass("active"))
    }, d.prototype.getMessage = function (a) {
        var c = this;
		
        $.ajax({
            url: window.budCNY.config.gatewayEndPoint + "&uuid=" + a,
            type: "GET",
            dataType: "jsonp",
            beforeSend: function () {
                c.loader.addClass("active")
            },
            success: function (a) {
                hb.dreamInputNb = a.message.length, zb.buildFinalMessage(a.message), zb.buildUrl(a), zb.sharingMailFirework(a.fromUser, a.toUser), window.budCNY.client.wechat && b(), zb.populateInput(a), u.to(c.loader, .2, {
                    alpha: 0,
                    ease: "easeIn",
                    delay: .4,
                    onComplete: function () {
                        c.loader.removeClass("active")
                    }
                }), c.startReceiveButton.addClass("active"), u.to(c.startReceiveButton, .6, {
                    alpha: 1,
                    ease: "easeIn",
                    delay: .6
                })
            },
            error: function () {
                window.location.href = window.location.origin + "/dreams.html"
            }
        })
    }, d.prototype.startReceive = function () {
        var a = this;
        u.to(this.startReceiveButton, .4, {
            alpha: 0, ease: "easeIn", onComplete: function () {
                hb.fireworkReady = !0, a.startReceiveButton.addClass("hide"), u.to(".receive-wrapper", .4, {
                    alpha: 1,
                    ease: "easeIn"
                }), setTimeout(function () {
                    pb ? (w.spriteAnim(".toast-container.receiving .sprite-anim.touch", 200, 58), window.budCNY.client.mobile ? $(".toast-copy").filter(".mobile-notoast").addClass("active") : $(".toast-copy").filter(".desktop-notoast").addClass("active")) : (w.spriteAnim(".toast-container.receiving .sprite-anim.toast", 220, 41), $(".toast-copy").filter(".mobile-toast").addClass("active"))
                }, 500)
            }
        })
    }, d.prototype.rotateDevice = function () {
        90 === window.orientation || -90 === window.orientation ? this.rotateDiv.hasClass("active") || (this.rotateDiv.addClass("active"), $(".backgrounds").addClass("hide"), $(".wrapper-container.active").addClass("hide")) : this.rotateDiv.hasClass("active") && (this.rotateDiv.removeClass("active"), $(".backgrounds").removeClass("hide"), $(".wrapper-container.active").removeClass("hide"))
    }, e.prototype.removeEmojis = function (a) {
        for (var b = "", c = a.val(), d = 0; d < c.length; d++) {
            var e = c.charCodeAt(d);
            if (!this.isVariationSelector(e) && !this.isCombiningSymbol(e))if (this.isSurrogate(e)) {
                var f = c.charCodeAt(++d);
                this.isEmoji(f) || (b += String.fromCharCode(e) + String.fromCharCode(f))
            } else this.isEmoji(e) || (b += String.fromCharCode(e))
        }
        b = b.trim(), c !== b && a.val(b)
    }, e.prototype.isSurrogate = function (a) {
        return a >= 55296 && 57343 >= a
    }, e.prototype.isVariationSelector = function (a) {
        return a >= 65024 && 65039 >= a
    }, e.prototype.isCombiningSymbol = function (a) {
        return a >= 8400 && 8447 >= a
    }, e.prototype.isEmoji = function (a) {
        var b = !1;
        return a >= 56320 && 63743 >= a && (b = !0), a >= 65520 && 65535 >= a && (b = !0), a >= 65024 && 65039 >= a && (b = !0), a >= 9728 && 9983 >= a && (b = !0), b
    }, e.prototype.minOne = function () {
        this.removeEmojis(this.inputFirst), this.removeEmojis(this.inputSecond), this.removeEmojis(this.inputThird), this.inputFirst.val().split(" ").join("").length < 1 ? this.flagMin && (this.flagMin = !1, this.launchLink.removeClass("active"), this.overlayLink.addClass("active"), this.cancelborderAnim(".launch")) : this.flagMin || (this.flagMin = !0, this.launchLink.addClass("active"), this.overlayLink.removeClass("active"), this.borderAnim(".launch"))
    }, e.prototype.countNbCharacter = function (a) {
        var b = 10 - a.length;
        this.counter.text(b)
    }, e.prototype.borderAnim = function (a) {
        u.to(a + " .fake-border.left", .15, {
            yPercent: "-100%", ease: "easeIn", onComplete: function () {
                u.to(a + " .fake-border.top", .15, {
                    xPercent: "100%", ease: "easeIn", onComplete: function () {
                        u.to(a + " .fake-border.right", .15, {
                            yPercent: "100%", ease: "easeIn", onComplete: function () {
                                u.to(a + " .fake-border.bottom", .15, {xPercent: "-100%", ease: "easeIn"})
                            }
                        })
                    }
                })
            }
        })
    }, e.prototype.cancelborderAnim = function (a) {
        u.to(a + " .fake-border.left", .1, {
            yPercent: "100%",
            ease: "easeIn"
        }), u.to(a + " .fake-border.top", .1, {
            xPercent: "-100%",
            ease: "easeIn"
        }), u.to(a + " .fake-border.right", .1, {
            yPercent: "-100%",
            ease: "easeIn"
        }), u.to(a + " .fake-border.bottom", .1, {xPercent: "100%", ease: "easeIn"})
    }, e.prototype.sendRequest = function () {
		  
        var c = this, d = this.inputFirst.val().length > 0 ? this.inputFirst.val() : "", e = 1, f = this.inputSecond.val().length > 0 ? this.inputSecond.val() : "", g = this.inputThird.val().length > 0 ? this.inputThird.val() : "";
	
        this.buildFinalMessage(this.inputFirst.val()), window.budCNY.util.getUrlParameterByName("channel").length > 0 && (e = window.budCNY.util.getUrlParameterByName("channel")), $.ajax({
            url: window.budCNY.config.gatewayEndPoint + "&message=" + d + "&channel=" + e + "&fromUser=" + f + "&toUser=" + g,
            type: "GET",
            dataType: "jsonp",
            success: function (d) {
				
                c.buildUrl(d), c.sharingMailFirework(c.inputSecond.val(), c.inputThird.val()), window.budCNY.client.wechat && (a(d), b())
            }
        })
    }, e.prototype.toastFromTo = function () {
        this.inputSecond.val().split(" ").join("").length < 1 && this.inputThird.val().split(" ").join("").length < 1 && this.toastInformations.filter(".no").addClass("active"), this.inputSecond.val().split(" ").join("").length > 0 && this.inputThird.val().split(" ").join("").length > 0 && (this.toastInformations.filter(".from-to").addClass("active"), this.toastFrom.text(this.inputSecond.val()), this.toastTo.text(this.inputThird.val())), this.inputSecond.val().split(" ").join("").length > 0 && this.inputThird.val().split(" ").join("").length < 1 && (this.toastInformations.filter(".from").addClass("active"), this.toastFrom.text(this.inputSecond.val())), this.inputSecond.val().split(" ").join("").length < 1 && this.inputThird.val().split(" ").join("").length > 0 && (this.toastInformations.filter(".to").addClass("active"), this.toastTo.text(this.inputThird.val()))
    }, e.prototype.populateInput = function (a) {
        this.inputFirst.val(a.message), this.inputSecond.val(a.fromUser), this.inputThird.val(a.toUser), this.toastFromTo()
    }, e.prototype.buildFinalMessage = function (a) {
        var b = new window.budCNY.FontManager;
        b.init(!1, window.budCNY.config.fontServerUrl), b.createDynamicFont("budFont", "HYLingXinJ", [a.toUpperCase()]), this.finalMessage.append("<span>" + a + "</span>")
    }, e.prototype.buildUrl = function (a) {
        window.budCNY.config.sharingUrl = window.location.protocol + "//" + window.location.host + "/ttd/" + a.uuid, this.copyLink.text(window.budCNY.config.sharingUrl), this.mobileCopyLink.text(window.budCNY.config.sharingUrl)
    }, e.prototype.sharingMailFirework = function (a, b) {
        var c = b.split(" ").join("").length > 0 ? "亲爱的" + b + "，" : "", d = a.split(" ").join("").length > 0 ? a : "", e = c + "别说我不关⼼你，刚为你许了个新年梦！", f = d + "为你许了个超闪新年梦，还有机会在纽约时代广场大屏幕耀眼绽放！立即观看：" + window.budCNY.config.sharingUrl;
        $(".mail-action").find("a").attr("href", "mailto:%20?subject=" + e + "&body=" + f), window.budCNY.config.wechat.link = window.budCNY.config.sharingUrl
    }, f.prototype.startAmbient = function () {
        window.Howler.usingWebAudio && (this.bgId = this.sprite.play("bg"), this.sprite.fade(0, .5, 2e3, this.bgId))
    }, f.prototype.throwLongFirework = function () {
        this.sprite.play("firework-short1")
    }, f.prototype.throwShortFirework1 = function () {
        this.sprite.play("firework-short1")
    }, f.prototype.throwShortFirework2 = function () {
        this.sprite.play("firework-short2")
    }, f.prototype.explode11 = function () {
        this.sprite.play("explosion-step1-1")
    }, f.prototype.explode12 = function () {
        this.sprite.play("explosion-step1-2")
    }, f.prototype.explode21 = function () {
        this.sprite.play("explosion-step2-1")
    }, f.prototype.explode22 = function () {
        this.sprite.play("explosion-step2-2")
    };
    var x = new f, y = function (a, b, c, d) {
        return a /= d, c * a * a + b
    }, z = function (a, b, c, d) {
        return a /= d, -c * a * (a - 2) + b
    }, A = function (a, b, c, d) {
        return c * (-Math.pow(2, -10 * a / d) + 1) + b
    }, B = function (a, b, c, d) {
        return a /= d / 2, 1 > a ? c / 2 * a * a + b : (a--, -c / 2 * (a * (a - 2) - 1) + b)
    }, C = 255, D = 215, E = 215, F = .8 * C, G = C / 32, H = function (a, b) {
        if ("number" == typeof a && "number" == typeof b)this.x = a, this.y = b; else {
            if (!(a instanceof H && 1 === arguments.length))throw new Error("Invalid arguments");
            this.x = a.x, this.y = a.y
        }
    };
    H.prototype.add = function (a) {
        this.x += a.x, this.y += a.y
    }, H.prototype.distance = function (a) {
        var b = this.x - a.x, c = this.y - a.y;
        return Math.sqrt(b * b + c * c)
    }, H.prototype.mult = function (a) {
        this.x *= a, this.y *= a
    }, H.prototype.multNew = function (a) {
        return new H(this.x * a, this.y * a)
    }, H.prototype.magnitude = function () {
        return Math.sqrt(this.x * this.x + this.y * this.y)
    }, H.prototype.round = function () {
        this.x = Math.round(10 * this.x) / 10, this.y = Math.round(10 * this.y) / 10
    };
    var I = function (a, b, c, d, e, f, g) {
        this.originalPosition = a, this.position = new H(a), this.originalVelocity = b, this.velocity = new H(b), this.finalSpeed = c, this.originalTtl = e || 255, this.ttl = e || 255, this.opacity = 1, this.originalSize = d, this.size = d || 2, this.easeOut = g, this.img = f, this.fallingLikeAshes = !1, this.fallingOffset = new H(Math.random() * Math.PI, Math.random())
    };
    I.prototype.update = function () {
        var a = Math.round(this.originalTtl / 2);
        this.fallingLikeAshes ? (this.position.x += Math.cos((this.originalTtl - this.ttl) / 32 + this.fallingOffset.x) / 4, this.position.y += this.velocity.y, this.ttl -= 6) : (this.velocity.x = this.easeOut(this.originalTtl - a - this.ttl, this.finalSpeed.x, -this.originalVelocity.x, a), this.velocity.y = this.easeOut(this.originalTtl - a - this.ttl, this.finalSpeed.y, -this.originalVelocity.y, a), this.position.add(this.velocity), Math.abs(this.velocity.y - this.finalSpeed.y) <= .1 && (this.fallingLikeAshes = !0, this.ttl *= window.budCNY.util.random(.5, 1)), this.ttl--), isFinite(this.position.x) || console.log("position is non finite"), this.position.round(), this.size = Math.max(0, z(this.originalTtl - this.ttl, this.originalSize, -this.originalSize, this.originalTtl) + .5 * window.budCNY.util.rnd(this.ttl * this.size / (this.originalTtl * this.originalSize), this.size))
    }, I.prototype.draw = function (a) {
        var b = a;
        b.save(), b.globalAlpha = this.opacity, b.drawImage(this.img, this.position.x - this.size / 2, this.position.y - this.size / 2, this.size, this.size), b.restore()
    }, I.prototype.isAlive = function () {
        return this.ttl >= 0
    }, I.prototype.reset = function () {
        this.position = new H(this.originalPosition), this.velocity = new H(this.originalVelocity), this.ttl = this.originalTtl, this.size = this.originalSize, this.fallingLikeAshes = !1
    };
    var J = function (a, b, c, d, e, f, g, h, i) {
        this.compositeOperation = h || "source-over", this.firstExplosionParticlesCount = a;
        var j = 5e-6 * b + .01;
        this.characterExplosionParticles = Math.min(i, Math.floor(5e3 * j)), this.characterParticleVelocityMagnitude = b / 128e3 + .00625, this.explosionParticleVelocityMagnitude = j, this.secondaryExplosionsCount = f, this.secondaryExplosionsParticlesCount = c, this.finalExplosionsParticleCount = e, this.mainExplosionsBufferSize = d, this.finalExplosionsBufferSize = g
    }, K = function (a) {
        this.position = a, this.ttl = G, this.size = 0, this.maxSize = 200
    };
    K.prototype.update = function () {
        var a = B(G - this.ttl, 0, 1, G);
        this.size = a * this.maxSize, this.ttl--
    }, K.prototype.draw = function (a) {
        a.save();
        var b = a.createRadialGradient(this.position.x, this.position.y, 0, this.position.x, this.position.y, this.size);
        b.addColorStop(0, "rgba(255, 255, 255, 0.2)"), b.addColorStop(.54, "rgba(255, 255, 255, 0.04)"), b.addColorStop(1, "rgba(255, 255, 255, 0.00)"), a.fillStyle = b, a.fillRect(this.position.x - this.size, this.position.y - this.size, 2 * this.size, 2 * this.size), a.restore()
    }, K.prototype.isAlive = function () {
        return this.ttl > 0
    }, K.prototype.reset = function () {
        this.ttl = G
    };
    var L = function (a) {
        this.position = a, this.ttl = F, this.opacity = 0, this.size = 0, this.maxSize = 400, this.maxOpacity = .6
    };
    L.prototype.update = function () {
        var a, b = F - 10, c = b - 5, d = c - 40;
        this.ttl > b ? (a = z(F - this.ttl, 0, 1, F - b), this.size = a * this.maxSize, this.opacity = a * this.maxOpacity) : this.ttl > c ? (a = -(c - this.ttl) / (b - c), this.size -= a, this.maxSize = this.size) : this.ttl > d ? (a = y(F - this.ttl, this.maxSize, 64, F - d), this.size = a) : (a = z(d - this.ttl, this.maxOpacity, -this.maxOpacity, d), this.opacity = a), this.ttl--
    }, L.prototype.draw = function (a) {
        a.save(), a.globalAlpha = this.opacity;
        var b = a.createRadialGradient(this.position.x, this.position.y, 0, this.position.x, this.position.y, this.size);
        b.addColorStop(0, "rgba(224, 90, 90, 0.19)"), b.addColorStop(.54, "rgba(212, 92, 98, 0.05)"), b.addColorStop(1, "rgba(255, 105, 109, 0.00)"), a.fillStyle = b, a.fillRect(this.position.x - this.size, this.position.y - this.size, 2 * this.size, 2 * this.size), a.restore()
    }, L.prototype.isAlive = function () {
        return this.ttl > 0
    }, L.prototype.reset = function () {
        this.ttl = F, this.size = 0
    };
    var M = function (a, b, c, d) {
        this.position = a, this.particles = b.slice(0), this.explosionLight = new K(this.position), this.alive = !0, this.explosionSound = c, this.exploded = !1, this.z = d || 1, this.translateVector = new H(this.position.x * (1 - this.z), this.position.y * (1 - this.z))
    };
    M.prototype.drawExplosionParticles = function (a, b) {
        var c, d, e = 0;
        for (c = this.particles.length - 1; c >= 0; c--)d = this.particles[c], d.isAlive() && (d.update(), d.draw(a, b), e++);
        return e
    }, M.prototype.draw = function (a, b) {
        this.isAlive() && (this.exploded || this.explode(), b.save(), a.save(), 1 !== this.z && (a.translate(this.translateVector.x, this.translateVector.y), b.translate(this.translateVector.x, this.translateVector.y), a.scale(this.z, this.z), b.scale(this.z, this.z)), this.explosionLight.isAlive() && (this.explosionLight.update(), this.explosionLight.draw(b)), this.alive = this.drawExplosionParticles(a, b) > 0, a.restore(), b.restore())
    }, M.prototype.isAlive = function () {
        return this.alive
    }, M.prototype.explode = function () {
        this.exploded = !0, this.explosionSound.call(x)
    }, M.prototype.reset = function () {
        var a, b;
        for (a = this.particles.length - 1; a >= 0; a--)b = this.particles[a], b.reset();
        this.explosionLight.reset(), this.exploded = !1, this.alive = !0
    };
    var N = new Image;
    N.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAADlUlEQVQ4T42VaW7bMBSEY1Fr9sXZ9yBAEOQqvUB7hN6nvUX7qz1KEAQIsttx9t0iRS2deZBcNUnTEiBkG/TH0fDNY6Pv36OBJQ4mnxxFOfP3/lotfmuNu76+rrrdrhobG3PSNJW1rusWt7e3ue/7+eHhYYqfOF+Nt8DOysqKPzAw4Hqe50ZR5IyOjqrqn3EcF8aYPEmSTGud4bPd29sjPKvTX4LV3NxcsLi4SKA/PDysgiBwBwcHHQ6lVGGtLbIsy6+urgR6d3eXnp+fJ6enp7auvg52FhYWgunpaX9qasofGRnxJiYmPLyygnowVQM2NKiWExalj4+P6eXlZXJ2dmaxkQE8qZT3wHj9kFAAw5mZGUKD8fFxj5YMDQ0pQJ08zwtOALPn52dRCr/tzc2Nubi4SO7v7zVsMbSkArtbW1sEBpOTk8Hs7GxIKL8D6hIehiErow++5vA5AzC5vr627XbbPDw8JK1WS3c6HbO9va1piYBx+gF8jJaWlgJ4HM3PzweE0hIcnI+qcGlJgUGPqRKvLkoJw9QHBwcGm2h4rlEtmuAGYcvLyyE8jugzDi/iIVI54ZxQLJXBSoCvFjYYTkDjk5MTgxnjd7OzsxNzA4LFBqiNoFKegIeAh4CHUE9bmlj3qQzGdyjFm7fM0dFRTLV80gqoJlzDjljAGxsb0draGmERVRLOye/coL+//zPWfSjr9Acq4uvx8XGMKtB8UiEV0wJs0KXPPTCsiFgZpQ095dzgBfgnwF8AEhjhmALFBt39/X29u7srihWsiFAN4erqKg8u5Ca0hb7XrPhYKv4GK9p8dVhAGD2mUlaFvEFlhUMvNzc3CZeDox30GXXNwLAEvfrhsWY5eXhUjIBofBZ4eXiiuI8WMBCARFDNUpNqYMkxgaxpDKeBwR6BcktRVgInmKmjaqg1T09PMUPyKiCASAJZblAq0UbfUFVA2CcQiAwps4wzJ4JiCC0r4ndAqpAwzlQKeIBQeGXEFcAuepCIQPvMEeecYGyQUjXhjDMjzkOsR5qfFXz1m80mg+EhiQyGi4pw0eEczhJcoCoy9gs8LQ7MVp6z7LBGLoBXbZNwKkWUCfTQOh1aAY9lbdU22S7ZiOCpxaHZemd7CyzK0TsEykaPQ1XwtYFeIf2YCwDN0IhybCJwvD7b5R9X1btXE6rFRRU4vJpKG9iT5WqCRRlOn7fGf19NZQ56j+oirYsgUNT/bfwCMWylRsVRTW4AAAAASUVORK5CYII=";
    var O = new Image;
    O.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAADcklEQVQ4T42Vy27TUBCGx3Ecx3GcFkq5lIsqQCzYwZLX4A36CDxIXwKx7GvAHgkWiCLEtU0pbRw3qXPl+4/tKEVtwdLo5GJ/85//zIw9+/dV45Z6eZvHOitjetmjuvGiK1w3CyKiaeZDqXHznCzzU7PpwGzyy2zEw/l5gPPA/jWz1lWzMDZrEHXAdWR6CqTPgE6HZuMTwEdmpwcE8Mlygr/BwaZZDKy5Zha1ACdEgGIFquc8PWedQBr3ARI58MEnM3K5HbhrGewDTa6gdoVgbSraBbhO1LDBY99TBapzQQ+BAh72zE6As4lC+QLM9pMbqMXX9i3AqwU8QnGIz3Us8PUAkmZIG5U2DH8DPQaI3ydfzPrYki2Dw0eAN4ACa98ksEI+xyQIS9U+KjzAE6BjFDooIAdl7f8A+sEsVX6nWCqBrqBU0I4SsIP29UK1rAnDsuSwYQI0R6WgWRfoXqn0G9CvfCZRX+DaPaBEQoLOXYIEHWxpE0mpvMVBBipo9unUCkhkqEx/sn436+0XqnufS3ADGzp3gKM2uc1KuJUEiX5bW212WltP2QjEV2/3uvvZMVBBUlT2BSWktIfPKXb0pLjxsFAstauCKQlgl0zq1188e9B4/hhnqK+dd92j7Te7AHqyAHBPCfQda9Jd1o+sAoeAHYToyBakue+CK9aWwKOd993D7de7KJRaKU0F1koiBxVc4AArnGIObGEDYFVJB5nJOlY0t55s1KjO4UusOMiOpZZwUA5MPqfYI+Wywin2BZVaHZYOT2sJjQG3qYqIkqNH6AYOTw1RHZ4SCErIa+3C2eLKTc0BUA0ioDswgDG/x2oSwE01CB3i0RyTqo2BD3SIZXX0VRUCqwyrzls0CCWXCMga0yCCRsyOoEEdz4tZMaPk3PChlgdVgwDNpB4byGv5oqXVJNq22lpRtbQmHC3dAKwy1iDQZFOTnCoEpwMHKM2AqwsFPjOE6vcBMhvUzppuLc2JagjReW5WjFFM943pW41NjUwNIA2iAY2hOXF2COkh1TTwCIURO2gyhzWAAqABHnvyGPBUgawRozPXLFYCoJps/FVc5w36uqAamaUNAe3sI0NjE5vdhNMsliU5NuRsX7P40kFfJXTqOURZ4evg9PbQj9WrSW8Q1Erhf7+aluH6LG91cO7wuKRa6vRSvfD6AykPckQRBMosAAAAAElFTkSuQmCC";
    var P = function (a, b, c, d, e, f) {
        if (this.content = b, this.position = a, this.height = 60, this.characterParticles = [], this.ttl = C, this.alive = !1, this.exploded = !1, this.bgLight = new L(this.position), this.firstStepExplosions = [], this.firstStepExplosions.push(new M(this.position, g(this.position, f.firstExplosionParticlesCount, f.explosionParticleVelocityMagnitude, C / 2, 1, A), d)), this.secondStepExplosions = [], P.prototype.init = function (a, b) {
                function c(a, b) {
                    b.clearRect(0, 0, k, l), b.fillStyle = "white", b.font = m.height + "px fireworkFont";
                    var c = a, e = b.measureText(c);
                    m.width = e.width, b.fillText(c, 0, m.height);
                    var f = d(b, 0, 0, Math.floor(e.width), 2 * m.height);
                    return f.length < 20 && (b.clearRect(0, 0, k, l), b.font = m.height + "px sans-serif", e = b.measureText(c), m.width = e.width, b.fillText(c, 0, m.height), f = d(b, 0, 0, Math.floor(e.width), 2 * m.height)), f
                }

                function d(a, b, c, d, f) {
                    for (var g = [], h = a.getImageData(b, c, d, f), i = 0; f > i; i++)for (var j = 0; d > j; j++) {
                        var k = e(h, j, i, 3);
                        k > 200 && g.push(new H(j, i))
                    }
                    return g
                }

                function e(a, b, c, d) {
                    var e = a.data;
                    return e[c * a.width * 4 + 4 * b + d]
                }

                function g(a, b, c, d) {
                    var e = c * a.width * 4 + 4 * b;
                    a.data[e] = d[0], a.data[e + 1] = d[1], a.data[e + 2] = d[2], a.data[e + 3] = d[3]
                }

                function h(a, c, d, e) {
                    b.canvas.width = c, b.canvas.height = d, b.clearRect(0, 0, b.canvas.width, b.canvas.height), e(a, c, d);
                    var f = new Image;
                    return f.src = b.canvas.toDataURL(), f
                }

                for (var i, j, k = 200, l = 200, m = this, n = c(a, b), o = n.length, p = b.createImageData(this.width, 2 * this.height), q = [255, 255, 255, 255], r = new H(0, 2), s = Math.min(f.characterExplosionParticles / o, .3), t = new H(this.width / 2, this.height / 2), u = 0; o > u; u++)if (i = n[u], Math.random() < .5 && g(p, i.x, i.y, q), Math.random() < s) {
                    var v = i.x - t.x, w = i.y - t.y, x = new H(i.x, i.y), y = Math.max(1, x.distance(t)), z = v / y * f.characterParticleVelocityMagnitude * Math.random(), B = w / y * f.characterParticleVelocityMagnitude * Math.random();
                    j = Math.min(20, Math.max(0, window.budCNY.util.rnd(15, 5)));
                    var D;
                    D = Math.random() < .2 ? O : N, this.characterParticles.push(new I(x, new H(z, B), r, j, C, D, A))
                }
                this.img = h(b, 3 * this.width, 2 * this.height, function (a) {
                    a.putImageData(p, 0, 0)
                })
            }, this.init(b, c), this.characterExplosion = new M(new H(this.width / 2, this.height / 2), this.characterParticles, e), f.secondaryExplosionsCount > 0) {
            var h = 150 * f.explosionParticleVelocityMagnitude, i = C / 4, j = Math.min(.2 * T, 150), k = Math.min(.2 * U, 150), l = new H(-j + this.width / 2, -k + this.height / 2);
            this.secondStepExplosions.push(new M(l, g(l, f.secondaryExplosionsParticlesCount, h, i, Math.random(), z), e)), l = new H(j + this.width / 2, -k + this.height / 2), this.secondStepExplosions.push(new M(l, g(l, f.secondaryExplosionsParticlesCount, h, i, Math.random(), z), e)), f.secondaryExplosionsCount > 2 && (l = new H(-j + this.width / 2, k + this.height / 2), this.secondStepExplosions.push(new M(l, g(l, f.secondaryExplosionsParticlesCount, h, i, Math.random(), z), e)), l = new H(j + this.width / 2, k + this.height / 2), this.secondStepExplosions.push(new M(l, g(l, f.secondaryExplosionsParticlesCount, h, i, Math.random(), z), e)))
        }
    };
    P.prototype.draw = function (a, b) {
        if (this.isAlive()) {
            this.exploded = !0, b.save(), a.save();
            var c;
            for (this.bgLight.isAlive() && (this.bgLight.update(), this.bgLight.draw(b)), c = this.firstStepExplosions.length; c--;)this.firstStepExplosions[c].isAlive() && this.firstStepExplosions[c].draw(a, b);
            if (this.ttl > D) {
                var d = (C - this.ttl) / (C - D), e = z(d, 0, 1, 1);
                b.translate(this.position.x - this.width / 2 * e, this.position.y - this.height / 2 * e), b.scale(e, e), b.drawImage(this.img, 0, 0)
            } else {
                c = this.secondStepExplosions.length;
                var f = !1;
                if (b.translate(this.position.x - this.width / 2, this.position.y - this.height / 2), a.translate(this.position.x - this.width / 2, this.position.y - this.height / 2), this.characterExplosion.draw(a, b), this.ttl < E)for (; c--;)this.secondStepExplosions[c].isAlive() && (this.secondStepExplosions[c].draw(a, b), f = !0);
                this.alive = this.characterExplosion.isAlive() || f
            }
            b.restore(), a.restore(), this.ttl--
        }
    }, P.prototype.isAlive = function () {
        return this.alive
    }, P.prototype.reset = function () {
        this.characterExplosion.reset();
        for (var a = this.firstStepExplosions.length; a--;)this.firstStepExplosions[a].reset();
        for (a = this.secondStepExplosions.length; a--;)this.secondStepExplosions[a].reset();
        this.ttl = C, this.alive = !1, this.exploded = !1, this.bgLight.reset()
    };
    var Q = function (a, b, c, d, e) {
        this.originalPosition = a, this.position = new H(a), this.destPosition = b, this.radius = 5;
        var f = Math.atan2(b.y - a.y, b.x - a.x);
        this.velocity = new H(Math.cos(f) * c, Math.sin(f) * c), this.reachTarget = !1, this.epsilonToTarget = d, this.launched = !1, this.launchSound = e
    };
    Q.prototype.update = function () {
        this.reachTarget = this.position.distance(this.destPosition) < this.epsilonToTarget, this.reachTarget || this.position.add(this.velocity)
    }, Q.prototype.draw = function (a) {
        if (this.isAlive()) {
            var b = .05, c = window.budCNY.util.rnd(.5, .07);
            a.save(), a.scale(b, c), a.translate(this.position.x / b, this.position.y / c), a.fillStyle = V, a.fillRect(0, 5, 200, 200), a.restore()
        }
    }, Q.prototype.isAlive = function () {
        return !this.reachTarget
    }, Q.prototype.launch = function () {
        this.launched = !0, this.launchSound.call(x)
    }, Q.prototype.reset = function () {
        this.exploded = !1, this.launched = !1, this.reachTarget = !1, this.position = new H(this.originalPosition)
    };
    var R = document.getElementById("canvas"), S = R.getContext("2d"), T = window.innerWidth, U = window.innerHeight;
    R.width = T, R.height = U;
    var V = S.createRadialGradient(100, 5, 0, 100, 100, 100);
    V.addColorStop(0, "white"), V.addColorStop(.5, "#b22a40"), V.addColorStop(1, "transparent"), h.prototype.update = function () {
        this.rocket.isAlive() || this.isExploded() || this.explode()
    }, h.prototype.explode = function () {
        this.character.alive = !0
    }, h.prototype.draw = function (a, b) {
        this.isAlive() && (b.save(), a.save(), 1 !== this.z && (a.translate(this.translateVector.x, this.translateVector.y), b.translate(this.translateVector.x, this.translateVector.y), a.scale(this.z, this.z), b.scale(this.z, this.z)), this.rocket.update(), this.rocket.draw(a, b), this.character.draw(a, b), a.restore(), b.restore())
    }, h.prototype.isExploded = function () {
        return this.character.exploded
    }, h.prototype.isAlive = function () {
        return this.rocket.launched && (this.rocket.isAlive() || this.character.isAlive())
    }, h.prototype.isLaunched = function () {
        return this.rocket.launched
    }, h.prototype.launch = function () {
        this.rocket.launch()
    }, h.prototype.reset = function () {
        this.launched = !1, this.rocket.reset(), this.character.reset()
    };
    var W = document.getElementById("finalCanvas"), X = W.getContext("2d"), Y = $(".final-message").width(), Z = $(".final-message").height();
    W.width = Y, W.height = Z;
    var _ = ($(".final-message").width() - $(".final-message").find("span").width()) / 2, ab = new i;
    l.prototype.start = function () {
        this.running || (this.running = !0, this.render())
    }, l.prototype.stop = function () {
        this.running && (this.running = !1)
    };
    var bb = window.budCNY.config.fpsMeter;
    bb && bb.showFps().show();
    var cb, db = ["HIGH_PERFORMANCE"], eb = ["MEDIUM_PERFORMANCE"], fb = ["LOW_PERFORMANCE"], gb = ["VERY_LOW_PERFORMANCE"], hb = new m(w), ib = document.getElementById("motionCanvas"), jb = ib.getContext("2d"), kb = $(".toast-container").width(), lb = $(".toast-container").height(), mb = [], nb = !1;
    ib.width = kb, ib.height = lb, jb.strokeStyle = "white", jb.lineJoin = "bevel", jb.lineWidth = 6;
    var ob = new d(w, x, hb);
    $(document).on("ready", function () {
        ob.init()
    }), $(window).on("touchmove", function (a) {
        a.preventDefault()
    }), $(window).on("resize", function () {
        s()
    }), window.addEventListener("orientationchange", function () {
        ob.rotateDevice(), s()
    }, !1), ob.rotateDevice();
    var pb = !1, qb = !1, rb = !1, sb = window.budCNY.client.iOS ? 1 : -1, tb = 0, ub = 0, vb = 0, wb = 0, xb = !1;
    window.budCNY.util.showQRcodeOverlay();
    var yb = Math.max(window.innerHeight, window.innerWidth) > 800;
    !yb && window.DeviceMotionEvent ? (window.ondevicemotion = function (a) {
        qb = (a.accelerationIncludingGravity.y || a.acceleration.y) * sb, qb ? (ob.spriteAnim.filter(".toast").hasClass("active") || ob.spriteAnim.filter(".toast").addClass("active"), hb.fireworkReady && hb.fireworkFlag && qb > 10 && (hb.fireworkFlag = !1, clearInterval(w.spriteInterval), w.hideInstructions(".toast-container." + ob.experienceMode), hb.throwMainFirework("accelerometer"), hb.moveUp())) : (rb = !0, t())
    }, setTimeout(function () {
        qb || rb || t()
    }, 1250)) : t(), $(".start-receive").on(window.budCNY.client.clickEvent, function (a) {
        a.preventDefault(), ob.startReceive(), x.startAmbient(), hb.createFireworks(cb), ob.motionCanvas.insertAfter(".wrapper-container.toast-container.receiving .toast-copy"), nb = !0, q()
    }), $(".launch-link").on(window.budCNY.client.clickEvent, function (a) {
        x.startAmbient(), a.preventDefault(), $(this).hasClass("active") && ($(".input-text").blur(), zb.sendRequest(), zb.toastFromTo(), hb.dreamInputNb = $(".input-text").filter(".dream").val().length, w.goToSection(".toast-container.sending"), u.to(".bud-title", .6, {
            alpha: 0,
            ease: "easeIn"
        }), pb && (nb = !0, q()), setTimeout(function () {
            hb.createFireworks(cb), pb ? (w.spriteAnim(".toast-container.sending .sprite-anim.touch", 200, 58), window.budCNY.client.mobile ? $(".toast-copy").filter(".mobile-notoast").addClass("active") : $(".toast-copy").filter(".desktop-notoast").addClass("active")) : (w.spriteAnim(".toast-container.sending .sprite-anim.toast", 220, 41), $(".toast-copy").filter(".mobile-toast").addClass("active"))
        }, 500))
    }), $(".replay").on(window.budCNY.client.clickEvent, function (a) {
        a.preventDefault(), ab.animationFinished = !0, hb.replayFirework()
    });
    var zb = new e;
    $(".input-text").on("input propertychange", function () {
        zb.minOne()
    }), $(".input-text.dream").on("input propertychange", function () {
        zb.countNbCharacter($(this).val())
    }), $(".input-text").on("focus", function () {
        $(this).val().length < 1 && $(this).parent().find(".legend").addClass("hide")
    }), $(".input-text").on("blur", function () {
        $(this).val().length < 1 && $(this).parent().find(".legend").removeClass("hide")
    }), $(".wechat-action").on(window.budCNY.client.clickEvent, function () {
        window.budCNY.client.wechat ? w.showWechatOverlay() : w.showDefaultOverlay()
    }), $(".cross").on(window.budCNY.client.clickEvent, function () {
        w.hideWechatOverlay(), w.hideDefaultOverlay()
    }), window.client.mobile ? $("#mobile-wechat-share").addClass("show") : $("#pc-wechat-share").addClass("show");
    var Ab = {};
    Ab.sendEvent = window.budCNY.util.noop, window.budCNY.Analytics && (Ab = new window.budCNY.Analytics), hb.registerListener({
        fireworkStart: function (a) {
            Ab.sendEvent("dreamentry.firework", ["start", a])
        }, fireworkEnd: function () {
            Ab.sendEvent("dreamentry.firework", "end")
        }
    }), v.registerListener({
        "wechat.menu.share.appmessage": function (a) {
            Ab.sendEvent("wechat.menu.share.appmessage", a)
        }, "wechat.menu.share.timeline": function (a) {
            Ab.sendEvent("wechat.menu.share.timeline", a)
        }, "wechat.menu.share.weibo": function (a) {
            Ab.sendEvent("wechat.menu.share.weibo", a)
        }
    })
}(window);