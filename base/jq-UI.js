/*! jQuery UI - v1.13.2 - 2022-11-10
 * http://jqueryui.com
 * Includes: widget.js, position.js, data.js, disable-selection.js, focusable.js, form-reset-mixin.js, jquery-patch.js, keycode.js, labels.js, scroll-parent.js, tabbable.js, unique-id.js, widgets/draggable.js, widgets/droppable.js, widgets/resizable.js, widgets/selectable.js, widgets/sortable.js, widgets/mouse.js, effect.js, effects/effect-blind.js, effects/effect-bounce.js, effects/effect-clip.js, effects/effect-drop.js, effects/effect-explode.js, effects/effect-fade.js, effects/effect-fold.js, effects/effect-highlight.js, effects/effect-puff.js, effects/effect-pulsate.js, effects/effect-scale.js, effects/effect-shake.js, effects/effect-size.js, effects/effect-slide.js, effects/effect-transfer.js
 * Copyright jQuery Foundation and other contributors; Licensed MIT */
! function(t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], t) : t(jQuery)
}(function(x) {
    "use strict";
    x.ui = x.ui || {};
    x.ui.version = "1.13.2";
    var o, i = 0,
        r = Array.prototype.hasOwnProperty,
        a = Array.prototype.slice;
    x.cleanData = (o = x.cleanData, function(t) {
        for (var e, i, s = 0; null != (i = t[s]); s++)(e = x._data(i, "events")) && e.remove && x(i).triggerHandler("remove");
        o(t)
    }), x.widget = function(t, i, e) {
        var s, o, n, r = {},
            a = t.split(".")[0],
            h = a + "-" + (t = t.split(".")[1]);
        return e || (e = i, i = x.Widget), Array.isArray(e) && (e = x.extend.apply(null, [{}].concat(e))), x.expr.pseudos[h.toLowerCase()] = function(t) {
            return !!x.data(t, h)
        }, x[a] = x[a] || {}, s = x[a][t], o = x[a][t] = function(t, e) {
            if (!this || !this._createWidget) return new o(t, e);
            arguments.length && this._createWidget(t, e)
        }, x.extend(o, s, {
            version: e.version,
            _proto: x.extend({}, e),
            _childConstructors: []
        }), (n = new i).options = x.widget.extend({}, n.options), x.each(e, function(e, s) {
            function o() {
                return i.prototype[e].apply(this, arguments)
            }

            function n(t) {
                return i.prototype[e].apply(this, t)
            }
            r[e] = "function" == typeof s ? function() {
                var t, e = this._super,
                    i = this._superApply;
                return this._super = o, this._superApply = n, t = s.apply(this, arguments), this._super = e, this._superApply = i, t
            } : s
        }), o.prototype = x.widget.extend(n, {
            widgetEventPrefix: s && n.widgetEventPrefix || t
        }, r, {
            constructor: o,
            namespace: a,
            widgetName: t,
            widgetFullName: h
        }), s ? (x.each(s._childConstructors, function(t, e) {
            var i = e.prototype;
            x.widget(i.namespace + "." + i.widgetName, o, e._proto)
        }), delete s._childConstructors) : i._childConstructors.push(o), x.widget.bridge(t, o), o
    }, x.widget.extend = function(t) {
        for (var e, i, s = a.call(arguments, 1), o = 0, n = s.length; o < n; o++)
            for (e in s[o]) i = s[o][e], r.call(s[o], e) && void 0 !== i && (x.isPlainObject(i) ? t[e] = x.isPlainObject(t[e]) ? x.widget.extend({}, t[e], i) : x.widget.extend({}, i) : t[e] = i);
        return t
    }, x.widget.bridge = function(n, e) {
        var r = e.prototype.widgetFullName || n;
        x.fn[n] = function(i) {
            var t = "string" == typeof i,
                s = a.call(arguments, 1),
                o = this;
            return t ? this.length || "instance" !== i ? this.each(function() {
                var t, e = x.data(this, r);
                return "instance" === i ? (o = e, !1) : e ? "function" != typeof e[i] || "_" === i.charAt(0) ? x.error("no such method '" + i + "' for " + n + " widget instance") : (t = e[i].apply(e, s)) !== e && void 0 !== t ? (o = t && t.jquery ? o.pushStack(t.get()) : t, !1) : void 0 : x.error("cannot call methods on " + n + " prior to initialization; attempted to call method '" + i + "'")
            }) : o = void 0 : (s.length && (i = x.widget.extend.apply(null, [i].concat(s))), this.each(function() {
                var t = x.data(this, r);
                t ? (t.option(i || {}), t._init && t._init()) : x.data(this, r, new e(i, this))
            })), o
        }
    }, x.Widget = function() {}, x.Widget._childConstructors = [], x.Widget.prototype = {
        widgetName: "widget",
        widgetEventPrefix: "",
        defaultElement: "<div>",
        options: {
            classes: {},
            disabled: !1,
            create: null
        },
        _createWidget: function(t, e) {
            e = x(e || this.defaultElement || this)[0], this.element = x(e), this.uuid = i++, this.eventNamespace = "." + this.widgetName + this.uuid, this.bindings = x(), this.hoverable = x(), this.focusable = x(), this.classesElementLookup = {}, e !== this && (x.data(e, this.widgetFullName, this), this._on(!0, this.element, {
                remove: function(t) {
                    t.target === e && this.destroy()
                }
            }), this.document = x(e.style ? e.ownerDocument : e.document || e), this.window = x(this.document[0].defaultView || this.document[0].parentWindow)), this.options = x.widget.extend({}, this.options, this._getCreateOptions(), t), this._create(), this.options.disabled && this._setOptionDisabled(this.options.disabled), this._trigger("create", null, this._getCreateEventData()), this._init()
        },
        _getCreateOptions: function() {
            return {}
        },
        _getCreateEventData: x.noop,
        _create: x.noop,
        _init: x.noop,
        destroy: function() {
            var i = this;
            this._destroy(), x.each(this.classesElementLookup, function(t, e) {
                i._removeClass(e, t)
            }), this.element.off(this.eventNamespace).removeData(this.widgetFullName), this.widget().off(this.eventNamespace).removeAttr("aria-disabled"), this.bindings.off(this.eventNamespace)
        },
        _destroy: x.noop,
        widget: function() {
            return this.element
        },
        option: function(t, e) {
            var i, s, o, n = t;
            if (0 === arguments.length) return x.widget.extend({}, this.options);
            if ("string" == typeof t)
                if (n = {}, t = (i = t.split(".")).shift(), i.length) {
                    for (s = n[t] = x.widget.extend({}, this.options[t]), o = 0; o < i.length - 1; o++) s[i[o]] = s[i[o]] || {}, s = s[i[o]];
                    if (t = i.pop(), 1 === arguments.length) return void 0 === s[t] ? null : s[t];
                    s[t] = e
                } else {
                    if (1 === arguments.length) return void 0 === this.options[t] ? null : this.options[t];
                    n[t] = e
                } return this._setOptions(n), this
        },
        _setOptions: function(t) {
            for (var e in t) this._setOption(e, t[e]);
            return this
        },
        _setOption: function(t, e) {
            return "classes" === t && this._setOptionClasses(e), this.options[t] = e, "disabled" === t && this._setOptionDisabled(e), this
        },
        _setOptionClasses: function(t) {
            var e, i, s;
            for (e in t) s = this.classesElementLookup[e], t[e] !== this.options.classes[e] && s && s.length && (i = x(s.get()), this._removeClass(s, e), i.addClass(this._classes({
                element: i,
                keys: e,
                classes: t,
                add: !0
            })))
        },
        _setOptionDisabled: function(t) {
            this._toggleClass(this.widget(), this.widgetFullName + "-disabled", null, !!t), t && (this._removeClass(this.hoverable, null, "ui-state-hover"), this._removeClass(this.focusable, null, "ui-state-focus"))
        },
        enable: function() {
            return this._setOptions({
                disabled: !1
            })
        },
        disable: function() {
            return this._setOptions({
                disabled: !0
            })
        },
        _classes: function(o) {
            var n = [],
                r = this;

            function t(t, e) {
                for (var i, s = 0; s < t.length; s++) i = r.classesElementLookup[t[s]] || x(), i = o.add ? (function() {
                    var i = [];
                    o.element.each(function(t, e) {
                        x.map(r.classesElementLookup, function(t) {
                            return t
                        }).some(function(t) {
                            return t.is(e)
                        }) || i.push(e)
                    }), r._on(x(i), {
                        remove: "_untrackClassesElement"
                    })
                }(), x(x.uniqueSort(i.get().concat(o.element.get())))) : x(i.not(o.element).get()), r.classesElementLookup[t[s]] = i, n.push(t[s]), e && o.classes[t[s]] && n.push(o.classes[t[s]])
            }
            return (o = x.extend({
                element: this.element,
                classes: this.options.classes || {}
            }, o)).keys && t(o.keys.match(/\S+/g) || [], !0), o.extra && t(o.extra.match(/\S+/g) || []), n.join(" ")
        },
        _untrackClassesElement: function(i) {
            var s = this;
            x.each(s.classesElementLookup, function(t, e) {
                -1 !== x.inArray(i.target, e) && (s.classesElementLookup[t] = x(e.not(i.target).get()))
            }), this._off(x(i.target))
        },
        _removeClass: function(t, e, i) {
            return this._toggleClass(t, e, i, !1)
        },
        _addClass: function(t, e, i) {
            return this._toggleClass(t, e, i, !0)
        },
        _toggleClass: function(t, e, i, s) {
            var o = "string" == typeof t || null === t,
                i = {
                    extra: o ? e : i,
                    keys: o ? t : e,
                    element: o ? this.element : t,
                    add: s = "boolean" == typeof s ? s : i
                };
            return i.element.toggleClass(this._classes(i), s), this
        },
        _on: function(o, n, t) {
            var r, a = this;
            "boolean" != typeof o && (t = n, n = o, o = !1), t ? (n = r = x(n), this.bindings = this.bindings.add(n)) : (t = n, n = this.element, r = this.widget()), x.each(t, function(t, e) {
                function i() {
                    if (o || !0 !== a.options.disabled && !x(this).hasClass("ui-state-disabled")) return ("string" == typeof e ? a[e] : e).apply(a, arguments)
                }
                "string" != typeof e && (i.guid = e.guid = e.guid || i.guid || x.guid++);
                var s = t.match(/^([\w:-]*)\s*(.*)$/),
                    t = s[1] + a.eventNamespace,
                    s = s[2];
                s ? r.on(t, s, i) : n.on(t, i)
            })
        },
        _off: function(t, e) {
            e = (e || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace, t.off(e), this.bindings = x(this.bindings.not(t).get()), this.focusable = x(this.focusable.not(t).get()), this.hoverable = x(this.hoverable.not(t).get())
        },
        _delay: function(t, e) {
            var i = this;
            return setTimeout(function() {
                return ("string" == typeof t ? i[t] : t).apply(i, arguments)
            }, e || 0)
        },
        _hoverable: function(t) {
            this.hoverable = this.hoverable.add(t), this._on(t, {
                mouseenter: function(t) {
                    this._addClass(x(t.currentTarget), null, "ui-state-hover")
                },
                mouseleave: function(t) {
                    this._removeClass(x(t.currentTarget), null, "ui-state-hover")
                }
            })
        },
        _focusable: function(t) {
            this.focusable = this.focusable.add(t), this._on(t, {
                focusin: function(t) {
                    this._addClass(x(t.currentTarget), null, "ui-state-focus")
                },
                focusout: function(t) {
                    this._removeClass(x(t.currentTarget), null, "ui-state-focus")
                }
            })
        },
        _trigger: function(t, e, i) {
            var s, o, n = this.options[t];
            if (i = i || {}, (e = x.Event(e)).type = (t === this.widgetEventPrefix ? t : this.widgetEventPrefix + t).toLowerCase(), e.target = this.element[0], o = e.originalEvent)
                for (s in o) s in e || (e[s] = o[s]);
            return this.element.trigger(e, i), !("function" == typeof n && !1 === n.apply(this.element[0], [e].concat(i)) || e.isDefaultPrevented())
        }
    }, x.each({
        show: "fadeIn",
        hide: "fadeOut"
    }, function(n, r) {
        x.Widget.prototype["_" + n] = function(e, t, i) {
            var s, o = (t = "string" == typeof t ? {
                effect: t
            } : t) ? !0 !== t && "number" != typeof t && t.effect || r : n;
            "number" == typeof(t = t || {}) ? t = {
                duration: t
            }: !0 === t && (t = {}), s = !x.isEmptyObject(t), t.complete = i, t.delay && e.delay(t.delay), s && x.effects && x.effects.effect[o] ? e[n](t) : o !== n && e[o] ? e[o](t.duration, t.easing, i) : e.queue(function(t) {
                x(this)[n](), i && i.call(e[0]), t()
            })
        }
    });
    var s, P, C, n, h, l, c, p, z;
    x.widget;

    function H(t, e, i) {
        return [parseFloat(t[0]) * (p.test(t[0]) ? e / 100 : 1), parseFloat(t[1]) * (p.test(t[1]) ? i / 100 : 1)]
    }

    function I(t, e) {
        return parseInt(x.css(t, e), 10) || 0
    }

    function S(t) {
        return null != t && t === t.window
    }
    P = Math.max, C = Math.abs, n = /left|center|right/, h = /top|center|bottom/, l = /[\+\-]\d+(\.[\d]+)?%?/, c = /^\w+/, p = /%$/, z = x.fn.position, x.position = {
        scrollbarWidth: function() {
            if (void 0 !== s) return s;
            var t, e = x("<div style='display:block;position:absolute;width:200px;height:200px;overflow:hidden;'><div style='height:300px;width:auto;'></div></div>"),
                i = e.children()[0];
            return x("body").append(e), t = i.offsetWidth, e.css("overflow", "scroll"), t === (i = i.offsetWidth) && (i = e[0].clientWidth), e.remove(), s = t - i
        },
        getScrollInfo: function(t) {
            var e = t.isWindow || t.isDocument ? "" : t.element.css("overflow-x"),
                i = t.isWindow || t.isDocument ? "" : t.element.css("overflow-y"),
                e = "scroll" === e || "auto" === e && t.width < t.element[0].scrollWidth;
            return {
                width: "scroll" === i || "auto" === i && t.height < t.element[0].scrollHeight ? x.position.scrollbarWidth() : 0,
                height: e ? x.position.scrollbarWidth() : 0
            }
        },
        getWithinInfo: function(t) {
            var e = x(t || window),
                i = S(e[0]),
                s = !!e[0] && 9 === e[0].nodeType;
            return {
                element: e,
                isWindow: i,
                isDocument: s,
                offset: !i && !s ? x(t).offset() : {
                    left: 0,
                    top: 0
                },
                scrollLeft: e.scrollLeft(),
                scrollTop: e.scrollTop(),
                width: e.outerWidth(),
                height: e.outerHeight()
            }
        }
    }, x.fn.position = function(p) {
        if (!p || !p.of) return z.apply(this, arguments);
        var f, u, d, g, m, t, v = "string" == typeof(p = x.extend({}, p)).of ? x(document).find(p.of) : x(p.of),
            _ = x.position.getWithinInfo(p.within),
            b = x.position.getScrollInfo(_),
            w = (p.collision || "flip").split(" "),
            y = {},
            e = 9 === (t = (e = v)[0]).nodeType ? {
                width: e.width(),
                height: e.height(),
                offset: {
                    top: 0,
                    left: 0
                }
            } : S(t) ? {
                width: e.width(),
                height: e.height(),
                offset: {
                    top: e.scrollTop(),
                    left: e.scrollLeft()
                }
            } : t.preventDefault ? {
                width: 0,
                height: 0,
                offset: {
                    top: t.pageY,
                    left: t.pageX
                }
            } : {
                width: e.outerWidth(),
                height: e.outerHeight(),
                offset: e.offset()
            };
        return v[0].preventDefault && (p.at = "left top"), u = e.width, d = e.height, m = x.extend({}, g = e.offset), x.each(["my", "at"], function() {
            var t, e, i = (p[this] || "").split(" ");
            (i = 1 === i.length ? n.test(i[0]) ? i.concat(["center"]) : h.test(i[0]) ? ["center"].concat(i) : ["center", "center"] : i)[0] = n.test(i[0]) ? i[0] : "center", i[1] = h.test(i[1]) ? i[1] : "center", t = l.exec(i[0]), e = l.exec(i[1]), y[this] = [t ? t[0] : 0, e ? e[0] : 0], p[this] = [c.exec(i[0])[0], c.exec(i[1])[0]]
        }), 1 === w.length && (w[1] = w[0]), "right" === p.at[0] ? m.left += u : "center" === p.at[0] && (m.left += u / 2), "bottom" === p.at[1] ? m.top += d : "center" === p.at[1] && (m.top += d / 2), f = H(y.at, u, d), m.left += f[0], m.top += f[1], this.each(function() {
            var i, t, r = x(this),
                a = r.outerWidth(),
                h = r.outerHeight(),
                e = I(this, "marginLeft"),
                s = I(this, "marginTop"),
                o = a + e + I(this, "marginRight") + b.width,
                n = h + s + I(this, "marginBottom") + b.height,
                l = x.extend({}, m),
                c = H(y.my, r.outerWidth(), r.outerHeight());
            "right" === p.my[0] ? l.left -= a : "center" === p.my[0] && (l.left -= a / 2), "bottom" === p.my[1] ? l.top -= h : "center" === p.my[1] && (l.top -= h / 2), l.left += c[0], l.top += c[1], i = {
                marginLeft: e,
                marginTop: s
            }, x.each(["left", "top"], function(t, e) {
                x.ui.position[w[t]] && x.ui.position[w[t]][e](l, {
                    targetWidth: u,
                    targetHeight: d,
                    elemWidth: a,
                    elemHeight: h,
                    collisionPosition: i,
                    collisionWidth: o,
                    collisionHeight: n,
                    offset: [f[0] + c[0], f[1] + c[1]],
                    my: p.my,
                    at: p.at,
                    within: _,
                    elem: r
                })
            }), p.using && (t = function(t) {
                var e = g.left - l.left,
                    i = e + u - a,
                    s = g.top - l.top,
                    o = s + d - h,
                    n = {
                        target: {
                            element: v,
                            left: g.left,
                            top: g.top,
                            width: u,
                            height: d
                        },
                        element: {
                            element: r,
                            left: l.left,
                            top: l.top,
                            width: a,
                            height: h
                        },
                        horizontal: i < 0 ? "left" : 0 < e ? "right" : "center",
                        vertical: o < 0 ? "top" : 0 < s ? "bottom" : "middle"
                    };
                u < a && C(e + i) < u && (n.horizontal = "center"), d < h && C(s + o) < d && (n.vertical = "middle"), P(C(e), C(i)) > P(C(s), C(o)) ? n.important = "horizontal" : n.important = "vertical", p.using.call(this, t, n)
            }), r.offset(x.extend(l, {
                using: t
            }))
        })
    }, x.ui.position = {
        fit: {
            left: function(t, e) {
                var i = e.within,
                    s = i.isWindow ? i.scrollLeft : i.offset.left,
                    o = i.width,
                    n = t.left - e.collisionPosition.marginLeft,
                    r = s - n,
                    a = n + e.collisionWidth - o - s;
                e.collisionWidth > o ? 0 < r && a <= 0 ? (i = t.left + r + e.collisionWidth - o - s, t.left += r - i) : t.left = !(0 < a && r <= 0) && a < r ? s + o - e.collisionWidth : s : 0 < r ? t.left += r : 0 < a ? t.left -= a : t.left = P(t.left - n, t.left)
            },
            top: function(t, e) {
                var i = e.within,
                    s = i.isWindow ? i.scrollTop : i.offset.top,
                    o = e.within.height,
                    n = t.top - e.collisionPosition.marginTop,
                    r = s - n,
                    a = n + e.collisionHeight - o - s;
                e.collisionHeight > o ? 0 < r && a <= 0 ? (i = t.top + r + e.collisionHeight - o - s, t.top += r - i) : t.top = !(0 < a && r <= 0) && a < r ? s + o - e.collisionHeight : s : 0 < r ? t.top += r : 0 < a ? t.top -= a : t.top = P(t.top - n, t.top)
            }
        },
        flip: {
            left: function(t, e) {
                var i = e.within,
                    s = i.offset.left + i.scrollLeft,
                    o = i.width,
                    n = i.isWindow ? i.scrollLeft : i.offset.left,
                    r = t.left - e.collisionPosition.marginLeft,
                    a = r - n,
                    h = r + e.collisionWidth - o - n,
                    l = "left" === e.my[0] ? -e.elemWidth : "right" === e.my[0] ? e.elemWidth : 0,
                    i = "left" === e.at[0] ? e.targetWidth : "right" === e.at[0] ? -e.targetWidth : 0,
                    r = -2 * e.offset[0];
                a < 0 ? ((s = t.left + l + i + r + e.collisionWidth - o - s) < 0 || s < C(a)) && (t.left += l + i + r) : 0 < h && (0 < (n = t.left - e.collisionPosition.marginLeft + l + i + r - n) || C(n) < h) && (t.left += l + i + r)
            },
            top: function(t, e) {
                var i = e.within,
                    s = i.offset.top + i.scrollTop,
                    o = i.height,
                    n = i.isWindow ? i.scrollTop : i.offset.top,
                    r = t.top - e.collisionPosition.marginTop,
                    a = r - n,
                    h = r + e.collisionHeight - o - n,
                    l = "top" === e.my[1] ? -e.elemHeight : "bottom" === e.my[1] ? e.elemHeight : 0,
                    i = "top" === e.at[1] ? e.targetHeight : "bottom" === e.at[1] ? -e.targetHeight : 0,
                    r = -2 * e.offset[1];
                a < 0 ? ((s = t.top + l + i + r + e.collisionHeight - o - s) < 0 || s < C(a)) && (t.top += l + i + r) : 0 < h && (0 < (n = t.top - e.collisionPosition.marginTop + l + i + r - n) || C(n) < h) && (t.top += l + i + r)
            }
        },
        flipfit: {
            left: function() {
                x.ui.position.flip.left.apply(this, arguments), x.ui.position.fit.left.apply(this, arguments)
            },
            top: function() {
                x.ui.position.flip.top.apply(this, arguments), x.ui.position.fit.top.apply(this, arguments)
            }
        }
    };
    var t;
    x.ui.position, x.extend(x.expr.pseudos, {
        data: x.expr.createPseudo ? x.expr.createPseudo(function(e) {
            return function(t) {
                return !!x.data(t, e)
            }
        }) : function(t, e, i) {
            return !!x.data(t, i[3])
        }
    }), x.fn.extend({
        disableSelection: (t = "onselectstart" in document.createElement("div") ? "selectstart" : "mousedown", function() {
            return this.on(t + ".ui-disableSelection", function(t) {
                t.preventDefault()
            })
        }),
        enableSelection: function() {
            return this.off(".ui-disableSelection")
        }
    });
    x.ui.focusable = function(t, e) {
        var i, s, o, n, r = t.nodeName.toLowerCase();
        return "area" === r ? (s = (i = t.parentNode).name, !(!t.href || !s || "map" !== i.nodeName.toLowerCase()) && (0 < (s = x("img[usemap='#" + s + "']")).length && s.is(":visible"))) : (/^(input|select|textarea|button|object)$/.test(r) ? (o = !t.disabled) && (n = x(t).closest("fieldset")[0]) && (o = !n.disabled) : o = "a" === r && t.href || e, o && x(t).is(":visible") && function(t) {
            var e = t.css("visibility");
            for (;
                "inherit" === e;) t = t.parent(), e = t.css("visibility");
            return "visible" === e
        }(x(t)))
    }, x.extend(x.expr.pseudos, {
        focusable: function(t) {
            return x.ui.focusable(t, null != x.attr(t, "tabindex"))
        }
    });
    var e, f;
    x.ui.focusable, x.fn._form = function() {
        return "string" == typeof this[0].form ? this.closest("form") : x(this[0].form)
    }, x.ui.formResetMixin = {
        _formResetHandler: function() {
            var e = x(this);
            setTimeout(function() {
                var t = e.data("ui-form-reset-instances");
                x.each(t, function() {
                    this.refresh()
                })
            })
        },
        _bindFormResetHandler: function() {
            var t;
            this.form = this.element._form(), this.form.length && ((t = this.form.data("ui-form-reset-instances") || []).length || this.form.on("reset.ui-form-reset", this._formResetHandler), t.push(this), this.form.data("ui-form-reset-instances", t))
        },
        _unbindFormResetHandler: function() {
            var t;
            this.form.length && ((t = this.form.data("ui-form-reset-instances")).splice(x.inArray(this, t), 1), t.length ? this.form.data("ui-form-reset-instances", t) : this.form.removeData("ui-form-reset-instances").off("reset.ui-form-reset"))
        }
    };
    x.expr.pseudos || (x.expr.pseudos = x.expr[":"]), x.uniqueSort || (x.uniqueSort = x.unique), x.escapeSelector || (e = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\x80-\uFFFF\w-]/g, f = function(t, e) {
        return e ? "\0" === t ? "�" : t.slice(0, -1) + "\\" + t.charCodeAt(t.length - 1).toString(16) + " " : "\\" + t
    }, x.escapeSelector = function(t) {
        return (t + "").replace(e, f)
    }), x.fn.even && x.fn.odd || x.fn.extend({
        even: function() {
            return this.filter(function(t) {
                return t % 2 == 0
            })
        },
        odd: function() {
            return this.filter(function(t) {
                return t % 2 == 1
            })
        }
    });
    x.ui.keyCode = {
        BACKSPACE: 8,
        COMMA: 188,
        DELETE: 46,
        DOWN: 40,
        END: 35,
        ENTER: 13,
        ESCAPE: 27,
        HOME: 36,
        LEFT: 37,
        PAGE_DOWN: 34,
        PAGE_UP: 33,
        PERIOD: 190,
        RIGHT: 39,
        SPACE: 32,
        TAB: 9,
        UP: 38
    }, x.fn.labels = function() {
        var t, e, i;
        return this.length ? this[0].labels && this[0].labels.length ? this.pushStack(this[0].labels) : (e = this.eq(0).parents("label"), (t = this.attr("id")) && (i = (i = this.eq(0).parents().last()).add((i.length ? i : this).siblings()), t = "label[for='" + x.escapeSelector(t) + "']", e = e.add(i.find(t).addBack(t))), this.pushStack(e)) : this.pushStack([])
    }, x.fn.scrollParent = function(t) {
        var e = this.css("position"),
            i = "absolute" === e,
            s = t ? /(auto|scroll|hidden)/ : /(auto|scroll)/,
            t = this.parents().filter(function() {
                var t = x(this);
                return (!i || "static" !== t.css("position")) && s.test(t.css("overflow") + t.css("overflow-y") + t.css("overflow-x"))
            }).eq(0);
        return "fixed" !== e && t.length ? t : x(this[0].ownerDocument || document)
    }, x.extend(x.expr.pseudos, {
        tabbable: function(t) {
            var e = x.attr(t, "tabindex"),
                i = null != e;
            return (!i || 0 <= e) && x.ui.focusable(t, i)
        }
    }), x.fn.extend({
        uniqueId: (u = 0, function() {
            return this.each(function() {
                this.id || (this.id = "ui-id-" + ++u)
            })
        }),
        removeUniqueId: function() {
            return this.each(function() {
                /^ui-id-\d+$/.test(this.id) && x(this).removeAttr("id")
            })
        }
    }), x.ui.ie = !!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase());
    var u, d = !1;
    x(document).on("mouseup", function() {
        d = !1
    });
    x.widget("ui.mouse", {
        version: "1.13.2",
        options: {
            cancel: "input, textarea, button, select, option",
            distance: 1,
            delay: 0
        },
        _mouseInit: function() {
            var e = this;
            this.element.on("mousedown." + this.widgetName, function(t) {
                return e._mouseDown(t)
            }).on("click." + this.widgetName, function(t) {
                if (!0 === x.data(t.target, e.widgetName + ".preventClickEvent")) return x.removeData(t.target, e.widgetName + ".preventClickEvent"), t.stopImmediatePropagation(), !1
            }), this.started = !1
        },
        _mouseDestroy: function() {
            this.element.off("." + this.widgetName), this._mouseMoveDelegate && this.document.off("mousemove." + this.widgetName, this._mouseMoveDelegate).off("mouseup." + this.widgetName, this._mouseUpDelegate)
        },
        _mouseDown: function(t) {
            if (!d) {
                this._mouseMoved = !1, this._mouseStarted && this._mouseUp(t), this._mouseDownEvent = t;
                var e = this,
                    i = 1 === t.which,
                    s = !("string" != typeof this.options.cancel || !t.target.nodeName) && x(t.target).closest(this.options.cancel).length;
                return i && !s && this._mouseCapture(t) ? (this.mouseDelayMet = !this.options.delay, this.mouseDelayMet || (this._mouseDelayTimer = setTimeout(function() {
                    e.mouseDelayMet = !0
                }, this.options.delay)), this._mouseDistanceMet(t) && this._mouseDelayMet(t) && (this._mouseStarted = !1 !== this._mouseStart(t), !this._mouseStarted) ? (t.preventDefault(), !0) : (!0 === x.data(t.target, this.widgetName + ".preventClickEvent") && x.removeData(t.target, this.widgetName + ".preventClickEvent"), this._mouseMoveDelegate = function(t) {
                    return e._mouseMove(t)
                }, this._mouseUpDelegate = function(t) {
                    return e._mouseUp(t)
                }, this.document.on("mousemove." + this.widgetName, this._mouseMoveDelegate).on("mouseup." + this.widgetName, this._mouseUpDelegate), t.preventDefault(), d = !0)) : !0
            }
        },
        _mouseMove: function(t) {
            if (this._mouseMoved) {
                if (x.ui.ie && (!document.documentMode || document.documentMode < 9) && !t.button) return this._mouseUp(t);
                if (!t.which)
                    if (t.originalEvent.altKey || t.originalEvent.ctrlKey || t.originalEvent.metaKey || t.originalEvent.shiftKey) this.ignoreMissingWhich = !0;
                    else if (!this.ignoreMissingWhich) return this._mouseUp(t)
            }
            return (t.which || t.button) && (this._mouseMoved = !0), this._mouseStarted ? (this._mouseDrag(t), t.preventDefault()) : (this._mouseDistanceMet(t) && this._mouseDelayMet(t) && (this._mouseStarted = !1 !== this._mouseStart(this._mouseDownEvent, t), this._mouseStarted ? this._mouseDrag(t) : this._mouseUp(t)), !this._mouseStarted)
        },
        _mouseUp: function(t) {
            this.document.off("mousemove." + this.widgetName, this._mouseMoveDelegate).off("mouseup." + this.widgetName, this._mouseUpDelegate), this._mouseStarted && (this._mouseStarted = !1, t.target === this._mouseDownEvent.target && x.data(t.target, this.widgetName + ".preventClickEvent", !0), this._mouseStop(t)), this._mouseDelayTimer && (clearTimeout(this._mouseDelayTimer), delete this._mouseDelayTimer), this.ignoreMissingWhich = !1, d = !1, t.preventDefault()
        },
        _mouseDistanceMet: function(t) {
            return Math.max(Math.abs(this._mouseDownEvent.pageX - t.pageX), Math.abs(this._mouseDownEvent.pageY - t.pageY)) >= this.options.distance
        },
        _mouseDelayMet: function() {
            return this.mouseDelayMet
        },
        _mouseStart: function() {},
        _mouseDrag: function() {},
        _mouseStop: function() {},
        _mouseCapture: function() {
            return !0
        }
    }), x.ui.plugin = {
        add: function(t, e, i) {
            var s, o = x.ui[t].prototype;
            for (s in i) o.plugins[s] = o.plugins[s] || [], o.plugins[s].push([e, i[s]])
        },
        call: function(t, e, i, s) {
            var o, n = t.plugins[e];
            if (n && (s || t.element[0].parentNode && 11 !== t.element[0].parentNode.nodeType))
                for (o = 0; o < n.length; o++) t.options[n[o][0]] && n[o][1].apply(t.element, i)
        }
    }, x.ui.safeActiveElement = function(e) {
        var i;
        try {
            i = e.activeElement
        } catch (t) {
            i = e.body
        }
        return i = !(i = i || e.body).nodeName ? e.body : i
    }, x.ui.safeBlur = function(t) {
        t && "body" !== t.nodeName.toLowerCase() && x(t).trigger("blur")
    };
    x.widget("ui.draggable", x.ui.mouse, {
        version: "1.13.2",
        widgetEventPrefix: "drag",
        options: {
            addClasses: !0,
            appendTo: "parent",
            axis: !1,
            connectToSortable: !1,
            containment: !1,
            cursor: "auto",
            cursorAt: !1,
            grid: !1,
            handle: !1,
            helper: "original",
            iframeFix: !1,
            opacity: !1,
            refreshPositions: !1,
            revert: !1,
            revertDuration: 500,
            scope: "default",
            scroll: !0,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            snap: !1,
            snapMode: "both",
            snapTolerance: 20,
            stack: !1,
            zIndex: !1,
            drag: null,
            start: null,
            stop: null
        },
        _create: function() {
            "original" === this.options.helper && this._setPositionRelative(), this.options.addClasses && this._addClass("ui-draggable"), this._setHandleClassName(), this._mouseInit()
        },
        _setOption: function(t, e) {
            this._super(t, e), "handle" === t && (this._removeHandleClassName(), this._setHandleClassName())
        },
        _destroy: function() {
            (this.helper || this.element).is(".ui-draggable-dragging") ? this.destroyOnClear = !0 : (this._removeHandleClassName(), this._mouseDestroy())
        },
        _mouseCapture: function(t) {
            var e = this.options;
            return !(this.helper || e.disabled || 0 < x(t.target).closest(".ui-resizable-handle").length) && (this.handle = this._getHandle(t), !!this.handle && (this._blurActiveElement(t), this._blockFrames(!0 === e.iframeFix ? "iframe" : e.iframeFix), !0))
        },
        _blockFrames: function(t) {
            this.iframeBlocks = this.document.find(t).map(function() {
                var t = x(this);
                return x("<div>").css("position", "absolute").appendTo(t.parent()).outerWidth(t.outerWidth()).outerHeight(t.outerHeight()).offset(t.offset())[0]
            })
        },
        _unblockFrames: function() {
            this.iframeBlocks && (this.iframeBlocks.remove(), delete this.iframeBlocks)
        },
        _blurActiveElement: function(t) {
            var e = x.ui.safeActiveElement(this.document[0]);
            x(t.target).closest(e).length || x.ui.safeBlur(e)
        },
        _mouseStart: function(t) {
            var e = this.options;
            return this.helper = this._createHelper(t), this._addClass(this.helper, "ui-draggable-dragging"), this._cacheHelperProportions(), x.ui.ddmanager && (x.ui.ddmanager.current = this), this._cacheMargins(), this.cssPosition = this.helper.css("position"), this.scrollParent = this.helper.scrollParent(!0), this.offsetParent = this.helper.offsetParent(), this.hasFixedAncestor = 0 < this.helper.parents().filter(function() {
                return "fixed" === x(this).css("position")
            }).length, this.positionAbs = this.element.offset(), this._refreshOffsets(t), this.originalPosition = this.position = this._generatePosition(t, !1), this.originalPageX = t.pageX, this.originalPageY = t.pageY, e.cursorAt && this._adjustOffsetFromHelper(e.cursorAt), this._setContainment(), !1 === this._trigger("start", t) ? (this._clear(), !1) : (this._cacheHelperProportions(), x.ui.ddmanager && !e.dropBehaviour && x.ui.ddmanager.prepareOffsets(this, t), this._mouseDrag(t, !0), x.ui.ddmanager && x.ui.ddmanager.dragStart(this, t), !0)
        },
        _refreshOffsets: function(t) {
            this.offset = {
                top: this.positionAbs.top - this.margins.top,
                left: this.positionAbs.left - this.margins.left,
                scroll: !1,
                parent: this._getParentOffset(),
                relative: this._getRelativeOffset()
            }, this.offset.click = {
                left: t.pageX - this.offset.left,
                top: t.pageY - this.offset.top
            }
        },
        _mouseDrag: function(t, e) {
            if (this.hasFixedAncestor && (this.offset.parent = this._getParentOffset()), this.position = this._generatePosition(t, !0), this.positionAbs = this._convertPositionTo("absolute"), !e) {
                e = this._uiHash();
                if (!1 === this._trigger("drag", t, e)) return this._mouseUp(new x.Event("mouseup", t)), !1;
                this.position = e.position
            }
            return this.helper[0].style.left = this.position.left + "px", this.helper[0].style.top = this.position.top + "px", x.ui.ddmanager && x.ui.ddmanager.drag(this, t), !1
        },
        _mouseStop: function(t) {
            var e = this,
                i = !1;
            return x.ui.ddmanager && !this.options.dropBehaviour && (i = x.ui.ddmanager.drop(this, t)), this.dropped && (i = this.dropped, this.dropped = !1), "invalid" === this.options.revert && !i || "valid" === this.options.revert && i || !0 === this.options.revert || "function" == typeof this.options.revert && this.options.revert.call(this.element, i) ? x(this.helper).animate(this.originalPosition, parseInt(this.options.revertDuration, 10), function() {
                !1 !== e._trigger("stop", t) && e._clear()
            }) : !1 !== this._trigger("stop", t) && this._clear(), !1
        },
        _mouseUp: function(t) {
            return this._unblockFrames(), x.ui.ddmanager && x.ui.ddmanager.dragStop(this, t), this.handleElement.is(t.target) && this.element.trigger("focus"), x.ui.mouse.prototype._mouseUp.call(this, t)
        },
        cancel: function() {
            return this.helper.is(".ui-draggable-dragging") ? this._mouseUp(new x.Event("mouseup", {
                target: this.element[0]
            })) : this._clear(), this
        },
        _getHandle: function(t) {
            return !this.options.handle || !!x(t.target).closest(this.element.find(this.options.handle)).length
        },
        _setHandleClassName: function() {
            this.handleElement = this.options.handle ? this.element.find(this.options.handle) : this.element, this._addClass(this.handleElement, "ui-draggable-handle")
        },
        _removeHandleClassName: function() {
            this._removeClass(this.handleElement, "ui-draggable-handle")
        },
        _createHelper: function(t) {
            var e = this.options,
                i = "function" == typeof e.helper,
                t = i ? x(e.helper.apply(this.element[0], [t])) : "clone" === e.helper ? this.element.clone().removeAttr("id") : this.element;
            return t.parents("body").length || t.appendTo("parent" === e.appendTo ? this.element[0].parentNode : e.appendTo), i && t[0] === this.element[0] && this._setPositionRelative(), t[0] === this.element[0] || /(fixed|absolute)/.test(t.css("position")) || t.css("position", "absolute"), t
        },
        _setPositionRelative: function() {
            /^(?:r|a|f)/.test(this.element.css("position")) || (this.element[0].style.position = "relative")
        },
        _adjustOffsetFromHelper: function(t) {
            "string" == typeof t && (t = t.split(" ")), "left" in (t = Array.isArray(t) ? {
                left: +t[0],
                top: +t[1] || 0
            } : t) && (this.offset.click.left = t.left + this.margins.left), "right" in t && (this.offset.click.left = this.helperProportions.width - t.right + this.margins.left), "top" in t && (this.offset.click.top = t.top + this.margins.top), "bottom" in t && (this.offset.click.top = this.helperProportions.height - t.bottom + this.margins.top)
        },
        _isRootNode: function(t) {
            return /(html|body)/i.test(t.tagName) || t === this.document[0]
        },
        _getParentOffset: function() {
            var t = this.offsetParent.offset(),
                e = this.document[0];
            return "absolute" === this.cssPosition && this.scrollParent[0] !== e && x.contains(this.scrollParent[0], this.offsetParent[0]) && (t.left += this.scrollParent.scrollLeft(), t.top += this.scrollParent.scrollTop()), {
                top: (t = this._isRootNode(this.offsetParent[0]) ? {
                    top: 0,
                    left: 0
                } : t).top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: t.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            }
        },
        _getRelativeOffset: function() {
            if ("relative" !== this.cssPosition) return {
                top: 0,
                left: 0
            };
            var t = this.element.position(),
                e = this._isRootNode(this.scrollParent[0]);
            return {
                top: t.top - (parseInt(this.helper.css("top"), 10) || 0) + (e ? 0 : this.scrollParent.scrollTop()),
                left: t.left - (parseInt(this.helper.css("left"), 10) || 0) + (e ? 0 : this.scrollParent.scrollLeft())
            }
        },
        _cacheMargins: function() {
            this.margins = {
                left: parseInt(this.element.css("marginLeft"), 10) || 0,
                top: parseInt(this.element.css("marginTop"), 10) || 0,
                right: parseInt(this.element.css("marginRight"), 10) || 0,
                bottom: parseInt(this.element.css("marginBottom"), 10) || 0
            }
        },
        _cacheHelperProportions: function() {
            this.helperProportions = {
                width: this.helper.outerWidth(),
                height: this.helper.outerHeight()
            }
        },
        _setContainment: function() {
            var t, e, i, s = this.options,
                o = this.document[0];
            this.relativeContainer = null, s.containment ? "window" !== s.containment ? "document" !== s.containment ? s.containment.constructor !== Array ? ("parent" === s.containment && (s.containment = this.helper[0].parentNode), (i = (e = x(s.containment))[0]) && (t = /(scroll|auto)/.test(e.css("overflow")), this.containment = [(parseInt(e.css("borderLeftWidth"), 10) || 0) + (parseInt(e.css("paddingLeft"), 10) || 0), (parseInt(e.css("borderTopWidth"), 10) || 0) + (parseInt(e.css("paddingTop"), 10) || 0), (t ? Math.max(i.scrollWidth, i.offsetWidth) : i.offsetWidth) - (parseInt(e.css("borderRightWidth"), 10) || 0) - (parseInt(e.css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left - this.margins.right, (t ? Math.max(i.scrollHeight, i.offsetHeight) : i.offsetHeight) - (parseInt(e.css("borderBottomWidth"), 10) || 0) - (parseInt(e.css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top - this.margins.bottom], this.relativeContainer = e)) : this.containment = s.containment : this.containment = [0, 0, x(o).width() - this.helperProportions.width - this.margins.left, (x(o).height() || o.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top] : this.containment = [x(window).scrollLeft() - this.offset.relative.left - this.offset.parent.left, x(window).scrollTop() - this.offset.relative.top - this.offset.parent.top, x(window).scrollLeft() + x(window).width() - this.helperProportions.width - this.margins.left, x(window).scrollTop() + (x(window).height() || o.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top] : this.containment = null
        },
        _convertPositionTo: function(t, e) {
            e = e || this.position;
            var i = "absolute" === t ? 1 : -1,
                t = this._isRootNode(this.scrollParent[0]);
            return {
                top: e.top + this.offset.relative.top * i + this.offset.parent.top * i - ("fixed" === this.cssPosition ? -this.offset.scroll.top : t ? 0 : this.offset.scroll.top) * i,
                left: e.left + this.offset.relative.left * i + this.offset.parent.left * i - ("fixed" === this.cssPosition ? -this.offset.scroll.left : t ? 0 : this.offset.scroll.left) * i
            }
        },
        _generatePosition: function(t, e) {
            var i, s = this.options,
                o = this._isRootNode(this.scrollParent[0]),
                n = t.pageX,
                r = t.pageY;
            return o && this.offset.scroll || (this.offset.scroll = {
                top: this.scrollParent.scrollTop(),
                left: this.scrollParent.scrollLeft()
            }), e && (this.containment && (i = this.relativeContainer ? (i = this.relativeContainer.offset(), [this.containment[0] + i.left, this.containment[1] + i.top, this.containment[2] + i.left, this.containment[3] + i.top]) : this.containment, t.pageX - this.offset.click.left < i[0] && (n = i[0] + this.offset.click.left), t.pageY - this.offset.click.top < i[1] && (r = i[1] + this.offset.click.top), t.pageX - this.offset.click.left > i[2] && (n = i[2] + this.offset.click.left), t.pageY - this.offset.click.top > i[3] && (r = i[3] + this.offset.click.top)), s.grid && (t = s.grid[1] ? this.originalPageY + Math.round((r - this.originalPageY) / s.grid[1]) * s.grid[1] : this.originalPageY, r = !i || t - this.offset.click.top >= i[1] || t - this.offset.click.top > i[3] ? t : t - this.offset.click.top >= i[1] ? t - s.grid[1] : t + s.grid[1], t = s.grid[0] ? this.originalPageX + Math.round((n - this.originalPageX) / s.grid[0]) * s.grid[0] : this.originalPageX, n = !i || t - this.offset.click.left >= i[0] || t - this.offset.click.left > i[2] ? t : t - this.offset.click.left >= i[0] ? t - s.grid[0] : t + s.grid[0]), "y" === s.axis && (n = this.originalPageX), "x" === s.axis && (r = this.originalPageY)), {
                top: r - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.offset.scroll.top : o ? 0 : this.offset.scroll.top),
                left: n - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.offset.scroll.left : o ? 0 : this.offset.scroll.left)
            }
        },
        _clear: function() {
            this._removeClass(this.helper, "ui-draggable-dragging"), this.helper[0] === this.element[0] || this.cancelHelperRemoval || this.helper.remove(), this.helper = null, this.cancelHelperRemoval = !1, this.destroyOnClear && this.destroy()
        },
        _trigger: function(t, e, i) {
            return i = i || this._uiHash(), x.ui.plugin.call(this, t, [e, i, this], !0), /^(drag|start|stop)/.test(t) && (this.positionAbs = this._convertPositionTo("absolute"), i.offset = this.positionAbs), x.Widget.prototype._trigger.call(this, t, e, i)
        },
        plugins: {},
        _uiHash: function() {
            return {
                helper: this.helper,
                position: this.position,
                originalPosition: this.originalPosition,
                offset: this.positionAbs
            }
        }
    }), x.ui.plugin.add("draggable", "connectToSortable", {
        start: function(e, t, i) {
            var s = x.extend({}, t, {
                item: i.element
            });
            i.sortables = [], x(i.options.connectToSortable).each(function() {
                var t = x(this).sortable("instance");
                t && !t.options.disabled && (i.sortables.push(t), t.refreshPositions(), t._trigger("activate", e, s))
            })
        },
        stop: function(e, t, i) {
            var s = x.extend({}, t, {
                item: i.element
            });
            i.cancelHelperRemoval = !1, x.each(i.sortables, function() {
                var t = this;
                t.isOver ? (t.isOver = 0, i.cancelHelperRemoval = !0, t.cancelHelperRemoval = !1, t._storedCSS = {
                    position: t.placeholder.css("position"),
                    top: t.placeholder.css("top"),
                    left: t.placeholder.css("left")
                }, t._mouseStop(e), t.options.helper = t.options._helper) : (t.cancelHelperRemoval = !0, t._trigger("deactivate", e, s))
            })
        },
        drag: function(i, s, o) {
            x.each(o.sortables, function() {
                var t = !1,
                    e = this;
                e.positionAbs = o.positionAbs, e.helperProportions = o.helperProportions, e.offset.click = o.offset.click, e._intersectsWith(e.containerCache) && (t = !0, x.each(o.sortables, function() {
                    return this.positionAbs = o.positionAbs, this.helperProportions = o.helperProportions, this.offset.click = o.offset.click, t = this !== e && this._intersectsWith(this.containerCache) && x.contains(e.element[0], this.element[0]) ? !1 : t
                })), t ? (e.isOver || (e.isOver = 1, o._parent = s.helper.parent(), e.currentItem = s.helper.appendTo(e.element).data("ui-sortable-item", !0), e.options._helper = e.options.helper, e.options.helper = function() {
                    return s.helper[0]
                }, i.target = e.currentItem[0], e._mouseCapture(i, !0), e._mouseStart(i, !0, !0), e.offset.click.top = o.offset.click.top, e.offset.click.left = o.offset.click.left, e.offset.parent.left -= o.offset.parent.left - e.offset.parent.left, e.offset.parent.top -= o.offset.parent.top - e.offset.parent.top, o._trigger("toSortable", i), o.dropped = e.element, x.each(o.sortables, function() {
                    this.refreshPositions()
                }), o.currentItem = o.element, e.fromOutside = o), e.currentItem && (e._mouseDrag(i), s.position = e.position)) : e.isOver && (e.isOver = 0, e.cancelHelperRemoval = !0, e.options._revert = e.options.revert, e.options.revert = !1, e._trigger("out", i, e._uiHash(e)), e._mouseStop(i, !0), e.options.revert = e.options._revert, e.options.helper = e.options._helper, e.placeholder && e.placeholder.remove(), s.helper.appendTo(o._parent), o._refreshOffsets(i), s.position = o._generatePosition(i, !0), o._trigger("fromSortable", i), o.dropped = !1, x.each(o.sortables, function() {
                    this.refreshPositions()
                }))
            })
        }
    }), x.ui.plugin.add("draggable", "cursor", {
        start: function(t, e, i) {
            var s = x("body"),
                i = i.options;
            s.css("cursor") && (i._cursor = s.css("cursor")), s.css("cursor", i.cursor)
        },
        stop: function(t, e, i) {
            i = i.options;
            i._cursor && x("body").css("cursor", i._cursor)
        }
    }), x.ui.plugin.add("draggable", "opacity", {
        start: function(t, e, i) {
            e = x(e.helper), i = i.options;
            e.css("opacity") && (i._opacity = e.css("opacity")), e.css("opacity", i.opacity)
        },
        stop: function(t, e, i) {
            i = i.options;
            i._opacity && x(e.helper).css("opacity", i._opacity)
        }
    }), x.ui.plugin.add("draggable", "scroll", {
        start: function(t, e, i) {
            i.scrollParentNotHidden || (i.scrollParentNotHidden = i.helper.scrollParent(!1)), i.scrollParentNotHidden[0] !== i.document[0] && "HTML" !== i.scrollParentNotHidden[0].tagName && (i.overflowOffset = i.scrollParentNotHidden.offset())
        },
        drag: function(t, e, i) {
            var s = i.options,
                o = !1,
                n = i.scrollParentNotHidden[0],
                r = i.document[0];
            n !== r && "HTML" !== n.tagName ? (s.axis && "x" === s.axis || (i.overflowOffset.top + n.offsetHeight - t.pageY < s.scrollSensitivity ? n.scrollTop = o = n.scrollTop + s.scrollSpeed : t.pageY - i.overflowOffset.top < s.scrollSensitivity && (n.scrollTop = o = n.scrollTop - s.scrollSpeed)), s.axis && "y" === s.axis || (i.overflowOffset.left + n.offsetWidth - t.pageX < s.scrollSensitivity ? n.scrollLeft = o = n.scrollLeft + s.scrollSpeed : t.pageX - i.overflowOffset.left < s.scrollSensitivity && (n.scrollLeft = o = n.scrollLeft - s.scrollSpeed))) : (s.axis && "x" === s.axis || (t.pageY - x(r).scrollTop() < s.scrollSensitivity ? o = x(r).scrollTop(x(r).scrollTop() - s.scrollSpeed) : x(window).height() - (t.pageY - x(r).scrollTop()) < s.scrollSensitivity && (o = x(r).scrollTop(x(r).scrollTop() + s.scrollSpeed))), s.axis && "y" === s.axis || (t.pageX - x(r).scrollLeft() < s.scrollSensitivity ? o = x(r).scrollLeft(x(r).scrollLeft() - s.scrollSpeed) : x(window).width() - (t.pageX - x(r).scrollLeft()) < s.scrollSensitivity && (o = x(r).scrollLeft(x(r).scrollLeft() + s.scrollSpeed)))), !1 !== o && x.ui.ddmanager && !s.dropBehaviour && x.ui.ddmanager.prepareOffsets(i, t)
        }
    }), x.ui.plugin.add("draggable", "snap", {
        start: function(t, e, i) {
            var s = i.options;
            i.snapElements = [], x(s.snap.constructor !== String ? s.snap.items || ":data(ui-draggable)" : s.snap).each(function() {
                var t = x(this),
                    e = t.offset();
                this !== i.element[0] && i.snapElements.push({
                    item: this,
                    width: t.outerWidth(),
                    height: t.outerHeight(),
                    top: e.top,
                    left: e.left
                })
            })
        },
        drag: function(t, e, i) {
            for (var s, o, n, r, a, h, l, c, p, f = i.options, u = f.snapTolerance, d = e.offset.left, g = d + i.helperProportions.width, m = e.offset.top, v = m + i.helperProportions.height, _ = i.snapElements.length - 1; 0 <= _; _--) h = (a = i.snapElements[_].left - i.margins.left) + i.snapElements[_].width, c = (l = i.snapElements[_].top - i.margins.top) + i.snapElements[_].height, g < a - u || h + u < d || v < l - u || c + u < m || !x.contains(i.snapElements[_].item.ownerDocument, i.snapElements[_].item) ? (i.snapElements[_].snapping && i.options.snap.release && i.options.snap.release.call(i.element, t, x.extend(i._uiHash(), {
                snapItem: i.snapElements[_].item
            })), i.snapElements[_].snapping = !1) : ("inner" !== f.snapMode && (s = Math.abs(l - v) <= u, o = Math.abs(c - m) <= u, n = Math.abs(a - g) <= u, r = Math.abs(h - d) <= u, s && (e.position.top = i._convertPositionTo("relative", {
                top: l - i.helperProportions.height,
                left: 0
            }).top), o && (e.position.top = i._convertPositionTo("relative", {
                top: c,
                left: 0
            }).top), n && (e.position.left = i._convertPositionTo("relative", {
                top: 0,
                left: a - i.helperProportions.width
            }).left), r && (e.position.left = i._convertPositionTo("relative", {
                top: 0,
                left: h
            }).left)), p = s || o || n || r, "outer" !== f.snapMode && (s = Math.abs(l - m) <= u, o = Math.abs(c - v) <= u, n = Math.abs(a - d) <= u, r = Math.abs(h - g) <= u, s && (e.position.top = i._convertPositionTo("relative", {
                top: l,
                left: 0
            }).top), o && (e.position.top = i._convertPositionTo("relative", {
                top: c - i.helperProportions.height,
                left: 0
            }).top), n && (e.position.left = i._convertPositionTo("relative", {
                top: 0,
                left: a
            }).left), r && (e.position.left = i._convertPositionTo("relative", {
                top: 0,
                left: h - i.helperProportions.width
            }).left)), !i.snapElements[_].snapping && (s || o || n || r || p) && i.options.snap.snap && i.options.snap.snap.call(i.element, t, x.extend(i._uiHash(), {
                snapItem: i.snapElements[_].item
            })), i.snapElements[_].snapping = s || o || n || r || p)
        }
    }), x.ui.plugin.add("draggable", "stack", {
        start: function(t, e, i) {
            var s, i = i.options,
                i = x.makeArray(x(i.stack)).sort(function(t, e) {
                    return (parseInt(x(t).css("zIndex"), 10) || 0) - (parseInt(x(e).css("zIndex"), 10) || 0)
                });
            i.length && (s = parseInt(x(i[0]).css("zIndex"), 10) || 0, x(i).each(function(t) {
                x(this).css("zIndex", s + t)
            }), this.css("zIndex", s + i.length))
        }
    }), x.ui.plugin.add("draggable", "zIndex", {
        start: function(t, e, i) {
            e = x(e.helper), i = i.options;
            e.css("zIndex") && (i._zIndex = e.css("zIndex")), e.css("zIndex", i.zIndex)
        },
        stop: function(t, e, i) {
            i = i.options;
            i._zIndex && x(e.helper).css("zIndex", i._zIndex)
        }
    });
    x.ui.draggable;

    function g(t, e, i) {
        return e <= t && t < e + i
    }
    x.widget("ui.droppable", {
        version: "1.13.2",
        widgetEventPrefix: "drop",
        options: {
            accept: "*",
            addClasses: !0,
            greedy: !1,
            scope: "default",
            tolerance: "intersect",
            activate: null,
            deactivate: null,
            drop: null,
            out: null,
            over: null
        },
        _create: function() {
            var t, e = this.options,
                i = e.accept;
            this.isover = !1, this.isout = !0, this.accept = "function" == typeof i ? i : function(t) {
                return t.is(i)
            }, this.proportions = function() {
                if (!arguments.length) return t = t || {
                    width: this.element[0].offsetWidth,
                    height: this.element[0].offsetHeight
                };
                t = arguments[0]
            }, this._addToManager(e.scope), e.addClasses && this._addClass("ui-droppable")
        },
        _addToManager: function(t) {
            x.ui.ddmanager.droppables[t] = x.ui.ddmanager.droppables[t] || [], x.ui.ddmanager.droppables[t].push(this)
        },
        _splice: function(t) {
            for (var e = 0; e < t.length; e++) t[e] === this && t.splice(e, 1)
        },
        _destroy: function() {
            var t = x.ui.ddmanager.droppables[this.options.scope];
            this._splice(t)
        },
        _setOption: function(t, e) {
            var i;
            "accept" === t ? this.accept = "function" == typeof e ? e : function(t) {
                return t.is(e)
            } : "scope" === t && (i = x.ui.ddmanager.droppables[this.options.scope], this._splice(i), this._addToManager(e)), this._super(t, e)
        },
        _activate: function(t) {
            var e = x.ui.ddmanager.current;
            this._addActiveClass(), e && this._trigger("activate", t, this.ui(e))
        },
        _deactivate: function(t) {
            var e = x.ui.ddmanager.current;
            this._removeActiveClass(), e && this._trigger("deactivate", t, this.ui(e))
        },
        _over: function(t) {
            var e = x.ui.ddmanager.current;
            e && (e.currentItem || e.element)[0] !== this.element[0] && this.accept.call(this.element[0], e.currentItem || e.element) && (this._addHoverClass(), this._trigger("over", t, this.ui(e)))
        },
        _out: function(t) {
            var e = x.ui.ddmanager.current;
            e && (e.currentItem || e.element)[0] !== this.element[0] && this.accept.call(this.element[0], e.currentItem || e.element) && (this._removeHoverClass(), this._trigger("out", t, this.ui(e)))
        },
        _drop: function(e, t) {
            var i = t || x.ui.ddmanager.current,
                s = !1;
            return !(!i || (i.currentItem || i.element)[0] === this.element[0]) && (this.element.find(":data(ui-droppable)").not(".ui-draggable-dragging").each(function() {
                var t = x(this).droppable("instance");
                if (t.options.greedy && !t.options.disabled && t.options.scope === i.options.scope && t.accept.call(t.element[0], i.currentItem || i.element) && x.ui.intersect(i, x.extend(t, {
                        offset: t.element.offset()
                    }), t.options.tolerance, e)) return !(s = !0)
            }), !s && (!!this.accept.call(this.element[0], i.currentItem || i.element) && (this._removeActiveClass(), this._removeHoverClass(), this._trigger("drop", e, this.ui(i)), this.element)))
        },
        ui: function(t) {
            return {
                draggable: t.currentItem || t.element,
                helper: t.helper,
                position: t.position,
                offset: t.positionAbs
            }
        },
        _addHoverClass: function() {
            this._addClass("ui-droppable-hover")
        },
        _removeHoverClass: function() {
            this._removeClass("ui-droppable-hover")
        },
        _addActiveClass: function() {
            this._addClass("ui-droppable-active")
        },
        _removeActiveClass: function() {
            this._removeClass("ui-droppable-active")
        }
    }), x.ui.intersect = function(t, e, i, s) {
        if (!e.offset) return !1;
        var o = (t.positionAbs || t.position.absolute).left + t.margins.left,
            n = (t.positionAbs || t.position.absolute).top + t.margins.top,
            r = o + t.helperProportions.width,
            a = n + t.helperProportions.height,
            h = e.offset.left,
            l = e.offset.top,
            c = h + e.proportions().width,
            p = l + e.proportions().height;
        switch (i) {
            case "fit":
                return h <= o && r <= c && l <= n && a <= p;
            case "intersect":
                return h < o + t.helperProportions.width / 2 && r - t.helperProportions.width / 2 < c && l < n + t.helperProportions.height / 2 && a - t.helperProportions.height / 2 < p;
            case "pointer":
                return g(s.pageY, l, e.proportions().height) && g(s.pageX, h, e.proportions().width);
            case "touch":
                return (l <= n && n <= p || l <= a && a <= p || n < l && p < a) && (h <= o && o <= c || h <= r && r <= c || o < h && c < r);
            default:
                return !1
        }
    }, !(x.ui.ddmanager = {
        current: null,
        droppables: {
            default: []
        },
        prepareOffsets: function(t, e) {
            var i, s, o = x.ui.ddmanager.droppables[t.options.scope] || [],
                n = e ? e.type : null,
                r = (t.currentItem || t.element).find(":data(ui-droppable)").addBack();
            t: for (i = 0; i < o.length; i++)
                if (!(o[i].options.disabled || t && !o[i].accept.call(o[i].element[0], t.currentItem || t.element))) {
                    for (s = 0; s < r.length; s++)
                        if (r[s] === o[i].element[0]) {
                            o[i].proportions().height = 0;
                            continue t
                        } o[i].visible = "none" !== o[i].element.css("display"), o[i].visible && ("mousedown" === n && o[i]._activate.call(o[i], e), o[i].offset = o[i].element.offset(), o[i].proportions({
                        width: o[i].element[0].offsetWidth,
                        height: o[i].element[0].offsetHeight
                    }))
                }
        },
        drop: function(t, e) {
            var i = !1;
            return x.each((x.ui.ddmanager.droppables[t.options.scope] || []).slice(), function() {
                this.options && (!this.options.disabled && this.visible && x.ui.intersect(t, this, this.options.tolerance, e) && (i = this._drop.call(this, e) || i), !this.options.disabled && this.visible && this.accept.call(this.element[0], t.currentItem || t.element) && (this.isout = !0, this.isover = !1, this._deactivate.call(this, e)))
            }), i
        },
        dragStart: function(t, e) {
            t.element.parentsUntil("body").on("scroll.droppable", function() {
                t.options.refreshPositions || x.ui.ddmanager.prepareOffsets(t, e)
            })
        },
        drag: function(o, n) {
            o.options.refreshPositions && x.ui.ddmanager.prepareOffsets(o, n), x.each(x.ui.ddmanager.droppables[o.options.scope] || [], function() {
                var t, e, i, s;
                this.options.disabled || this.greedyChild || !this.visible || (s = !(i = x.ui.intersect(o, this, this.options.tolerance, n)) && this.isover ? "isout" : i && !this.isover ? "isover" : null) && (this.options.greedy && (e = this.options.scope, (i = this.element.parents(":data(ui-droppable)").filter(function() {
                    return x(this).droppable("instance").options.scope === e
                })).length && ((t = x(i[0]).droppable("instance")).greedyChild = "isover" === s)), t && "isover" === s && (t.isover = !1, t.isout = !0, t._out.call(t, n)), this[s] = !0, this["isout" === s ? "isover" : "isout"] = !1, this["isover" === s ? "_over" : "_out"].call(this, n), t && "isout" === s && (t.isout = !1, t.isover = !0, t._over.call(t, n)))
            })
        },
        dragStop: function(t, e) {
            t.element.parentsUntil("body").off("scroll.droppable"), t.options.refreshPositions || x.ui.ddmanager.prepareOffsets(t, e)
        }
    }) !== x.uiBackCompat && x.widget("ui.droppable", x.ui.droppable, {
        options: {
            hoverClass: !1,
            activeClass: !1
        },
        _addActiveClass: function() {
            this._super(), this.options.activeClass && this.element.addClass(this.options.activeClass)
        },
        _removeActiveClass: function() {
            this._super(), this.options.activeClass && this.element.removeClass(this.options.activeClass)
        },
        _addHoverClass: function() {
            this._super(), this.options.hoverClass && this.element.addClass(this.options.hoverClass)
        },
        _removeHoverClass: function() {
            this._super(), this.options.hoverClass && this.element.removeClass(this.options.hoverClass)
        }
    });
    x.ui.droppable;
    x.widget("ui.resizable", x.ui.mouse, {
        version: "1.13.2",
        widgetEventPrefix: "resize",
        options: {
            alsoResize: !1,
            animate: !1,
            animateDuration: "slow",
            animateEasing: "swing",
            aspectRatio: !1,
            autoHide: !1,
            classes: {
                "ui-resizable-se": "ui-icon ui-icon-gripsmall-diagonal-se"
            },
            containment: !1,
            ghost: !1,
            grid: !1,
            handles: "e,s,se",
            helper: !1,
            maxHeight: null,
            maxWidth: null,
            minHeight: 10,
            minWidth: 10,
            zIndex: 90,
            resize: null,
            start: null,
            stop: null
        },
        _num: function(t) {
            return parseFloat(t) || 0
        },
        _isNumber: function(t) {
            return !isNaN(parseFloat(t))
        },
        _hasScroll: function(t, e) {
            if ("hidden" === x(t).css("overflow")) return !1;
            var i = e && "left" === e ? "scrollLeft" : "scrollTop",
                e = !1;
            if (0 < t[i]) return !0;
            try {
                t[i] = 1, e = 0 < t[i], t[i] = 0
            } catch (t) {}
            return e
        },
        _create: function() {
            var t, e = this.options,
                i = this;
            this._addClass("ui-resizable"), x.extend(this, {
                _aspectRatio: !!e.aspectRatio,
                aspectRatio: e.aspectRatio,
                originalElement: this.element,
                _proportionallyResizeElements: [],
                _helper: e.helper || e.ghost || e.animate ? e.helper || "ui-resizable-helper" : null
            }), this.element[0].nodeName.match(/^(canvas|textarea|input|select|button|img)$/i) && (this.element.wrap(x("<div class='ui-wrapper'></div>").css({
                overflow: "hidden",
                position: this.element.css("position"),
                width: this.element.outerWidth(),
                height: this.element.outerHeight(),
                top: this.element.css("top"),
                left: this.element.css("left")
            })), this.element = this.element.parent().data("ui-resizable", this.element.resizable("instance")), this.elementIsWrapper = !0, t = {
                marginTop: this.originalElement.css("marginTop"),
                marginRight: this.originalElement.css("marginRight"),
                marginBottom: this.originalElement.css("marginBottom"),
                marginLeft: this.originalElement.css("marginLeft")
            }, this.element.css(t), this.originalElement.css("margin", 0), this.originalResizeStyle = this.originalElement.css("resize"), this.originalElement.css("resize", "none"), this._proportionallyResizeElements.push(this.originalElement.css({
                position: "static",
                zoom: 1,
                display: "block"
            })), this.originalElement.css(t), this._proportionallyResize()), this._setupHandles(), e.autoHide && x(this.element).on("mouseenter", function() {
                e.disabled || (i._removeClass("ui-resizable-autohide"), i._handles.show())
            }).on("mouseleave", function() {
                e.disabled || i.resizing || (i._addClass("ui-resizable-autohide"), i._handles.hide())
            }), this._mouseInit()
        },
        _destroy: function() {
            this._mouseDestroy(), this._addedHandles.remove();

            function t(t) {
                x(t).removeData("resizable").removeData("ui-resizable").off(".resizable")
            }
            var e;
            return this.elementIsWrapper && (t(this.element), e = this.element, this.originalElement.css({
                position: e.css("position"),
                width: e.outerWidth(),
                height: e.outerHeight(),
                top: e.css("top"),
                left: e.css("left")
            }).insertAfter(e), e.remove()), this.originalElement.css("resize", this.originalResizeStyle), t(this.originalElement), this
        },
        _setOption: function(t, e) {
            switch (this._super(t, e), t) {
                case "handles":
                    this._removeHandles(), this._setupHandles();
                    break;
                case "aspectRatio":
                    this._aspectRatio = !!e
            }
        },
        _setupHandles: function() {
            var t, e, i, s, o, n = this.options,
                r = this;
            if (this.handles = n.handles || (x(".ui-resizable-handle", this.element).length ? {
                    n: ".ui-resizable-n",
                    e: ".ui-resizable-e",
                    s: ".ui-resizable-s",
                    w: ".ui-resizable-w",
                    se: ".ui-resizable-se",
                    sw: ".ui-resizable-sw",
                    ne: ".ui-resizable-ne",
                    nw: ".ui-resizable-nw"
                } : "e,s,se"), this._handles = x(), this._addedHandles = x(), this.handles.constructor === String)
                for ("all" === this.handles && (this.handles = "n,e,s,w,se,sw,ne,nw"), i = this.handles.split(","), this.handles = {}, e = 0; e < i.length; e++) s = "ui-resizable-" + (t = String.prototype.trim.call(i[e])), o = x("<div>"), this._addClass(o, "ui-resizable-handle " + s), o.css({
                    zIndex: n.zIndex
                }), this.handles[t] = ".ui-resizable-" + t, this.element.children(this.handles[t]).length || (this.element.append(o), this._addedHandles = this._addedHandles.add(o));
            this._renderAxis = function(t) {
                var e, i, s;
                for (e in t = t || this.element, this.handles) this.handles[e].constructor === String ? this.handles[e] = this.element.children(this.handles[e]).first().show() : (this.handles[e].jquery || this.handles[e].nodeType) && (this.handles[e] = x(this.handles[e]), this._on(this.handles[e], {
                    mousedown: r._mouseDown
                })), this.elementIsWrapper && this.originalElement[0].nodeName.match(/^(textarea|input|select|button)$/i) && (i = x(this.handles[e], this.element), s = /sw|ne|nw|se|n|s/.test(e) ? i.outerHeight() : i.outerWidth(), i = ["padding", /ne|nw|n/.test(e) ? "Top" : /se|sw|s/.test(e) ? "Bottom" : /^e$/.test(e) ? "Right" : "Left"].join(""), t.css(i, s), this._proportionallyResize()), this._handles = this._handles.add(this.handles[e])
            }, this._renderAxis(this.element), this._handles = this._handles.add(this.element.find(".ui-resizable-handle")), this._handles.disableSelection(), this._handles.on("mouseover", function() {
                r.resizing || (this.className && (o = this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i)), r.axis = o && o[1] ? o[1] : "se")
            }), n.autoHide && (this._handles.hide(), this._addClass("ui-resizable-autohide"))
        },
        _removeHandles: function() {
            this._addedHandles.remove()
        },
        _mouseCapture: function(t) {
            var e, i, s = !1;
            for (e in this.handles)(i = x(this.handles[e])[0]) !== t.target && !x.contains(i, t.target) || (s = !0);
            return !this.options.disabled && s
        },
        _mouseStart: function(t) {
            var e, i, s = this.options,
                o = this.element;
            return this.resizing = !0, this._renderProxy(), e = this._num(this.helper.css("left")), i = this._num(this.helper.css("top")), s.containment && (e += x(s.containment).scrollLeft() || 0, i += x(s.containment).scrollTop() || 0), this.offset = this.helper.offset(), this.position = {
                left: e,
                top: i
            }, this.size = this._helper ? {
                width: this.helper.width(),
                height: this.helper.height()
            } : {
                width: o.width(),
                height: o.height()
            }, this.originalSize = this._helper ? {
                width: o.outerWidth(),
                height: o.outerHeight()
            } : {
                width: o.width(),
                height: o.height()
            }, this.sizeDiff = {
                width: o.outerWidth() - o.width(),
                height: o.outerHeight() - o.height()
            }, this.originalPosition = {
                left: e,
                top: i
            }, this.originalMousePosition = {
                left: t.pageX,
                top: t.pageY
            }, this.aspectRatio = "number" == typeof s.aspectRatio ? s.aspectRatio : this.originalSize.width / this.originalSize.height || 1, s = x(".ui-resizable-" + this.axis).css("cursor"), x("body").css("cursor", "auto" === s ? this.axis + "-resize" : s), this._addClass("ui-resizable-resizing"), this._propagate("start", t), !0
        },
        _mouseDrag: function(t) {
            var e = this.originalMousePosition,
                i = this.axis,
                s = t.pageX - e.left || 0,
                e = t.pageY - e.top || 0,
                i = this._change[i];
            return this._updatePrevProperties(), i && (e = i.apply(this, [t, s, e]), this._updateVirtualBoundaries(t.shiftKey), (this._aspectRatio || t.shiftKey) && (e = this._updateRatio(e, t)), e = this._respectSize(e, t), this._updateCache(e), this._propagate("resize", t), e = this._applyChanges(), !this._helper && this._proportionallyResizeElements.length && this._proportionallyResize(), x.isEmptyObject(e) || (this._updatePrevProperties(), this._trigger("resize", t, this.ui()), this._applyChanges())), !1
        },
        _mouseStop: function(t) {
            this.resizing = !1;
            var e, i, s, o = this.options,
                n = this;
            return this._helper && (s = (e = (i = this._proportionallyResizeElements).length && /textarea/i.test(i[0].nodeName)) && this._hasScroll(i[0], "left") ? 0 : n.sizeDiff.height, i = e ? 0 : n.sizeDiff.width, e = {
                width: n.helper.width() - i,
                height: n.helper.height() - s
            }, i = parseFloat(n.element.css("left")) + (n.position.left - n.originalPosition.left) || null, s = parseFloat(n.element.css("top")) + (n.position.top - n.originalPosition.top) || null, o.animate || this.element.css(x.extend(e, {
                top: s,
                left: i
            })), n.helper.height(n.size.height), n.helper.width(n.size.width), this._helper && !o.animate && this._proportionallyResize()), x("body").css("cursor", "auto"), this._removeClass("ui-resizable-resizing"), this._propagate("stop", t), this._helper && this.helper.remove(), !1
        },
        _updatePrevProperties: function() {
            this.prevPosition = {
                top: this.position.top,
                left: this.position.left
            }, this.prevSize = {
                width: this.size.width,
                height: this.size.height
            }
        },
        _applyChanges: function() {
            var t = {};
            return this.position.top !== this.prevPosition.top && (t.top = this.position.top + "px"), this.position.left !== this.prevPosition.left && (t.left = this.position.left + "px"), this.size.width !== this.prevSize.width && (t.width = this.size.width + "px"), this.size.height !== this.prevSize.height && (t.height = this.size.height + "px"), this.helper.css(t), t
        },
        _updateVirtualBoundaries: function(t) {
            var e, i, s = this.options,
                o = {
                    minWidth: this._isNumber(s.minWidth) ? s.minWidth : 0,
                    maxWidth: this._isNumber(s.maxWidth) ? s.maxWidth : 1 / 0,
                    minHeight: this._isNumber(s.minHeight) ? s.minHeight : 0,
                    maxHeight: this._isNumber(s.maxHeight) ? s.maxHeight : 1 / 0
                };
            (this._aspectRatio || t) && (e = o.minHeight * this.aspectRatio, i = o.minWidth / this.aspectRatio, s = o.maxHeight * this.aspectRatio, t = o.maxWidth / this.aspectRatio, e > o.minWidth && (o.minWidth = e), i > o.minHeight && (o.minHeight = i), s < o.maxWidth && (o.maxWidth = s), t < o.maxHeight && (o.maxHeight = t)), this._vBoundaries = o
        },
        _updateCache: function(t) {
            this.offset = this.helper.offset(), this._isNumber(t.left) && (this.position.left = t.left), this._isNumber(t.top) && (this.position.top = t.top), this._isNumber(t.height) && (this.size.height = t.height), this._isNumber(t.width) && (this.size.width = t.width)
        },
        _updateRatio: function(t) {
            var e = this.position,
                i = this.size,
                s = this.axis;
            return this._isNumber(t.height) ? t.width = t.height * this.aspectRatio : this._isNumber(t.width) && (t.height = t.width / this.aspectRatio), "sw" === s && (t.left = e.left + (i.width - t.width), t.top = null), "nw" === s && (t.top = e.top + (i.height - t.height), t.left = e.left + (i.width - t.width)), t
        },
        _respectSize: function(t) {
            var e = this._vBoundaries,
                i = this.axis,
                s = this._isNumber(t.width) && e.maxWidth && e.maxWidth < t.width,
                o = this._isNumber(t.height) && e.maxHeight && e.maxHeight < t.height,
                n = this._isNumber(t.width) && e.minWidth && e.minWidth > t.width,
                r = this._isNumber(t.height) && e.minHeight && e.minHeight > t.height,
                a = this.originalPosition.left + this.originalSize.width,
                h = this.originalPosition.top + this.originalSize.height,
                l = /sw|nw|w/.test(i),
                i = /nw|ne|n/.test(i);
            return n && (t.width = e.minWidth), r && (t.height = e.minHeight), s && (t.width = e.maxWidth), o && (t.height = e.maxHeight), n && l && (t.left = a - e.minWidth), s && l && (t.left = a - e.maxWidth), r && i && (t.top = h - e.minHeight), o && i && (t.top = h - e.maxHeight), t.width || t.height || t.left || !t.top ? t.width || t.height || t.top || !t.left || (t.left = null) : t.top = null, t
        },
        _getPaddingPlusBorderDimensions: function(t) {
            for (var e = 0, i = [], s = [t.css("borderTopWidth"), t.css("borderRightWidth"), t.css("borderBottomWidth"), t.css("borderLeftWidth")], o = [t.css("paddingTop"), t.css("paddingRight"), t.css("paddingBottom"), t.css("paddingLeft")]; e < 4; e++) i[e] = parseFloat(s[e]) || 0, i[e] += parseFloat(o[e]) || 0;
            return {
                height: i[0] + i[2],
                width: i[1] + i[3]
            }
        },
        _proportionallyResize: function() {
            if (this._proportionallyResizeElements.length)
                for (var t, e = 0, i = this.helper || this.element; e < this._proportionallyResizeElements.length; e++) t = this._proportionallyResizeElements[e], this.outerDimensions || (this.outerDimensions = this._getPaddingPlusBorderDimensions(t)), t.css({
                    height: i.height() - this.outerDimensions.height || 0,
                    width: i.width() - this.outerDimensions.width || 0
                })
        },
        _renderProxy: function() {
            var t = this.element,
                e = this.options;
            this.elementOffset = t.offset(), this._helper ? (this.helper = this.helper || x("<div></div>").css({
                overflow: "hidden"
            }), this._addClass(this.helper, this._helper), this.helper.css({
                width: this.element.outerWidth(),
                height: this.element.outerHeight(),
                position: "absolute",
                left: this.elementOffset.left + "px",
                top: this.elementOffset.top + "px",
                zIndex: ++e.zIndex
            }), this.helper.appendTo("body").disableSelection()) : this.helper = this.element
        },
        _change: {
            e: function(t, e) {
                return {
                    width: this.originalSize.width + e
                }
            },
            w: function(t, e) {
                var i = this.originalSize;
                return {
                    left: this.originalPosition.left + e,
                    width: i.width - e
                }
            },
            n: function(t, e, i) {
                var s = this.originalSize;
                return {
                    top: this.originalPosition.top + i,
                    height: s.height - i
                }
            },
            s: function(t, e, i) {
                return {
                    height: this.originalSize.height + i
                }
            },
            se: function(t, e, i) {
                return x.extend(this._change.s.apply(this, arguments), this._change.e.apply(this, [t, e, i]))
            },
            sw: function(t, e, i) {
                return x.extend(this._change.s.apply(this, arguments), this._change.w.apply(this, [t, e, i]))
            },
            ne: function(t, e, i) {
                return x.extend(this._change.n.apply(this, arguments), this._change.e.apply(this, [t, e, i]))
            },
            nw: function(t, e, i) {
                return x.extend(this._change.n.apply(this, arguments), this._change.w.apply(this, [t, e, i]))
            }
        },
        _propagate: function(t, e) {
            x.ui.plugin.call(this, t, [e, this.ui()]), "resize" !== t && this._trigger(t, e, this.ui())
        },
        plugins: {},
        ui: function() {
            return {
                originalElement: this.originalElement,
                element: this.element,
                helper: this.helper,
                position: this.position,
                size: this.size,
                originalSize: this.originalSize,
                originalPosition: this.originalPosition
            }
        }
    }), x.ui.plugin.add("resizable", "animate", {
        stop: function(e) {
            var i = x(this).resizable("instance"),
                t = i.options,
                s = i._proportionallyResizeElements,
                o = s.length && /textarea/i.test(s[0].nodeName),
                n = o && i._hasScroll(s[0], "left") ? 0 : i.sizeDiff.height,
                r = o ? 0 : i.sizeDiff.width,
                o = {
                    width: i.size.width - r,
                    height: i.size.height - n
                },
                r = parseFloat(i.element.css("left")) + (i.position.left - i.originalPosition.left) || null,
                n = parseFloat(i.element.css("top")) + (i.position.top - i.originalPosition.top) || null;
            i.element.animate(x.extend(o, n && r ? {
                top: n,
                left: r
            } : {}), {
                duration: t.animateDuration,
                easing: t.animateEasing,
                step: function() {
                    var t = {
                        width: parseFloat(i.element.css("width")),
                        height: parseFloat(i.element.css("height")),
                        top: parseFloat(i.element.css("top")),
                        left: parseFloat(i.element.css("left"))
                    };
                    s && s.length && x(s[0]).css({
                        width: t.width,
                        height: t.height
                    }), i._updateCache(t), i._propagate("resize", e)
                }
            })
        }
    }), x.ui.plugin.add("resizable", "containment", {
        start: function() {
            var i, s, o = x(this).resizable("instance"),
                t = o.options,
                e = o.element,
                n = t.containment,
                r = n instanceof x ? n.get(0) : /parent/.test(n) ? e.parent().get(0) : n;
            r && (o.containerElement = x(r), /document/.test(n) || n === document ? (o.containerOffset = {
                left: 0,
                top: 0
            }, o.containerPosition = {
                left: 0,
                top: 0
            }, o.parentData = {
                element: x(document),
                left: 0,
                top: 0,
                width: x(document).width(),
                height: x(document).height() || document.body.parentNode.scrollHeight
            }) : (i = x(r), s = [], x(["Top", "Right", "Left", "Bottom"]).each(function(t, e) {
                s[t] = o._num(i.css("padding" + e))
            }), o.containerOffset = i.offset(), o.containerPosition = i.position(), o.containerSize = {
                height: i.innerHeight() - s[3],
                width: i.innerWidth() - s[1]
            }, t = o.containerOffset, e = o.containerSize.height, n = o.containerSize.width, n = o._hasScroll(r, "left") ? r.scrollWidth : n, e = o._hasScroll(r) ? r.scrollHeight : e, o.parentData = {
                element: r,
                left: t.left,
                top: t.top,
                width: n,
                height: e
            }))
        },
        resize: function(t) {
            var e = x(this).resizable("instance"),
                i = e.options,
                s = e.containerOffset,
                o = e.position,
                n = e._aspectRatio || t.shiftKey,
                r = {
                    top: 0,
                    left: 0
                },
                a = e.containerElement,
                t = !0;
            a[0] !== document && /static/.test(a.css("position")) && (r = s), o.left < (e._helper ? s.left : 0) && (e.size.width = e.size.width + (e._helper ? e.position.left - s.left : e.position.left - r.left), n && (e.size.height = e.size.width / e.aspectRatio, t = !1), e.position.left = i.helper ? s.left : 0), o.top < (e._helper ? s.top : 0) && (e.size.height = e.size.height + (e._helper ? e.position.top - s.top : e.position.top), n && (e.size.width = e.size.height * e.aspectRatio, t = !1), e.position.top = e._helper ? s.top : 0), i = e.containerElement.get(0) === e.element.parent().get(0), o = /relative|absolute/.test(e.containerElement.css("position")), i && o ? (e.offset.left = e.parentData.left + e.position.left, e.offset.top = e.parentData.top + e.position.top) : (e.offset.left = e.element.offset().left, e.offset.top = e.element.offset().top), o = Math.abs(e.sizeDiff.width + (e._helper ? e.offset.left - r.left : e.offset.left - s.left)), s = Math.abs(e.sizeDiff.height + (e._helper ? e.offset.top - r.top : e.offset.top - s.top)), o + e.size.width >= e.parentData.width && (e.size.width = e.parentData.width - o, n && (e.size.height = e.size.width / e.aspectRatio, t = !1)), s + e.size.height >= e.parentData.height && (e.size.height = e.parentData.height - s, n && (e.size.width = e.size.height * e.aspectRatio, t = !1)), t || (e.position.left = e.prevPosition.left, e.position.top = e.prevPosition.top, e.size.width = e.prevSize.width, e.size.height = e.prevSize.height)
        },
        stop: function() {
            var t = x(this).resizable("instance"),
                e = t.options,
                i = t.containerOffset,
                s = t.containerPosition,
                o = t.containerElement,
                n = x(t.helper),
                r = n.offset(),
                a = n.outerWidth() - t.sizeDiff.width,
                n = n.outerHeight() - t.sizeDiff.height;
            t._helper && !e.animate && /relative/.test(o.css("position")) && x(this).css({
                left: r.left - s.left - i.left,
                width: a,
                height: n
            }), t._helper && !e.animate && /static/.test(o.css("position")) && x(this).css({
                left: r.left - s.left - i.left,
                width: a,
                height: n
            })
        }
    }), x.ui.plugin.add("resizable", "alsoResize", {
        start: function() {
            var t = x(this).resizable("instance").options;
            x(t.alsoResize).each(function() {
                var t = x(this);
                t.data("ui-resizable-alsoresize", {
                    width: parseFloat(t.width()),
                    height: parseFloat(t.height()),
                    left: parseFloat(t.css("left")),
                    top: parseFloat(t.css("top"))
                })
            })
        },
        resize: function(t, i) {
            var e = x(this).resizable("instance"),
                s = e.options,
                o = e.originalSize,
                n = e.originalPosition,
                r = {
                    height: e.size.height - o.height || 0,
                    width: e.size.width - o.width || 0,
                    top: e.position.top - n.top || 0,
                    left: e.position.left - n.left || 0
                };
            x(s.alsoResize).each(function() {
                var t = x(this),
                    s = x(this).data("ui-resizable-alsoresize"),
                    o = {},
                    e = t.parents(i.originalElement[0]).length ? ["width", "height"] : ["width", "height", "top", "left"];
                x.each(e, function(t, e) {
                    var i = (s[e] || 0) + (r[e] || 0);
                    i && 0 <= i && (o[e] = i || null)
                }), t.css(o)
            })
        },
        stop: function() {
            x(this).removeData("ui-resizable-alsoresize")
        }
    }), x.ui.plugin.add("resizable", "ghost", {
        start: function() {
            var t = x(this).resizable("instance"),
                e = t.size;
            t.ghost = t.originalElement.clone(), t.ghost.css({
                opacity: .25,
                display: "block",
                position: "relative",
                height: e.height,
                width: e.width,
                margin: 0,
                left: 0,
                top: 0
            }), t._addClass(t.ghost, "ui-resizable-ghost"), !1 !== x.uiBackCompat && "string" == typeof t.options.ghost && t.ghost.addClass(this.options.ghost), t.ghost.appendTo(t.helper)
        },
        resize: function() {
            var t = x(this).resizable("instance");
            t.ghost && t.ghost.css({
                position: "relative",
                height: t.size.height,
                width: t.size.width
            })
        },
        stop: function() {
            var t = x(this).resizable("instance");
            t.ghost && t.helper && t.helper.get(0).removeChild(t.ghost.get(0))
        }
    }), x.ui.plugin.add("resizable", "grid", {
        resize: function() {
            var t, e = x(this).resizable("instance"),
                i = e.options,
                s = e.size,
                o = e.originalSize,
                n = e.originalPosition,
                r = e.axis,
                a = "number" == typeof i.grid ? [i.grid, i.grid] : i.grid,
                h = a[0] || 1,
                l = a[1] || 1,
                c = Math.round((s.width - o.width) / h) * h,
                p = Math.round((s.height - o.height) / l) * l,
                f = o.width + c,
                u = o.height + p,
                d = i.maxWidth && i.maxWidth < f,
                g = i.maxHeight && i.maxHeight < u,
                m = i.minWidth && i.minWidth > f,
                s = i.minHeight && i.minHeight > u;
            i.grid = a, m && (f += h), s && (u += l), d && (f -= h), g && (u -= l), /^(se|s|e)$/.test(r) ? (e.size.width = f, e.size.height = u) : /^(ne)$/.test(r) ? (e.size.width = f, e.size.height = u, e.position.top = n.top - p) : /^(sw)$/.test(r) ? (e.size.width = f, e.size.height = u, e.position.left = n.left - c) : ((u - l <= 0 || f - h <= 0) && (t = e._getPaddingPlusBorderDimensions(this)), 0 < u - l ? (e.size.height = u, e.position.top = n.top - p) : (u = l - t.height, e.size.height = u, e.position.top = n.top + o.height - u), 0 < f - h ? (e.size.width = f, e.position.left = n.left - c) : (f = h - t.width, e.size.width = f, e.position.left = n.left + o.width - f))
        }
    });
    x.ui.resizable, x.widget("ui.selectable", x.ui.mouse, {
        version: "1.13.2",
        options: {
            appendTo: "body",
            autoRefresh: !0,
            distance: 0,
            filter: "*",
            tolerance: "touch",
            selected: null,
            selecting: null,
            start: null,
            stop: null,
            unselected: null,
            unselecting: null
        },
        _create: function() {
            var i = this;
            this._addClass("ui-selectable"), this.dragged = !1, this.refresh = function() {
                i.elementPos = x(i.element[0]).offset(), i.selectees = x(i.options.filter, i.element[0]), i._addClass(i.selectees, "ui-selectee"), i.selectees.each(function() {
                    var t = x(this),
                        e = t.offset(),
                        e = {
                            left: e.left - i.elementPos.left,
                            top: e.top - i.elementPos.top
                        };
                    x.data(this, "selectable-item", {
                        element: this,
                        $element: t,
                        left: e.left,
                        top: e.top,
                        right: e.left + t.outerWidth(),
                        bottom: e.top + t.outerHeight(),
                        startselected: !1,
                        selected: t.hasClass("ui-selected"),
                        selecting: t.hasClass("ui-selecting"),
                        unselecting: t.hasClass("ui-unselecting")
                    })
                })
            }, this.refresh(), this._mouseInit(), this.helper = x("<div>"), this._addClass(this.helper, "ui-selectable-helper")
        },
        _destroy: function() {
            this.selectees.removeData("selectable-item"), this._mouseDestroy()
        },
        _mouseStart: function(i) {
            var s = this,
                t = this.options;
            this.opos = [i.pageX, i.pageY], this.elementPos = x(this.element[0]).offset(), this.options.disabled || (this.selectees = x(t.filter, this.element[0]), this._trigger("start", i), x(t.appendTo).append(this.helper), this.helper.css({
                left: i.pageX,
                top: i.pageY,
                width: 0,
                height: 0
            }), t.autoRefresh && this.refresh(), this.selectees.filter(".ui-selected").each(function() {
                var t = x.data(this, "selectable-item");
                t.startselected = !0, i.metaKey || i.ctrlKey || (s._removeClass(t.$element, "ui-selected"), t.selected = !1, s._addClass(t.$element, "ui-unselecting"), t.unselecting = !0, s._trigger("unselecting", i, {
                    unselecting: t.element
                }))
            }), x(i.target).parents().addBack().each(function() {
                var t, e = x.data(this, "selectable-item");
                if (e) return t = !i.metaKey && !i.ctrlKey || !e.$element.hasClass("ui-selected"), s._removeClass(e.$element, t ? "ui-unselecting" : "ui-selected")._addClass(e.$element, t ? "ui-selecting" : "ui-unselecting"), e.unselecting = !t, e.selecting = t, (e.selected = t) ? s._trigger("selecting", i, {
                    selecting: e.element
                }) : s._trigger("unselecting", i, {
                    unselecting: e.element
                }), !1
            }))
        },
        _mouseDrag: function(s) {
            if (this.dragged = !0, !this.options.disabled) {
                var t, o = this,
                    n = this.options,
                    r = this.opos[0],
                    a = this.opos[1],
                    h = s.pageX,
                    l = s.pageY;
                return h < r && (t = h, h = r, r = t), l < a && (t = l, l = a, a = t), this.helper.css({
                    left: r,
                    top: a,
                    width: h - r,
                    height: l - a
                }), this.selectees.each(function() {
                    var t = x.data(this, "selectable-item"),
                        e = !1,
                        i = {};
                    t && t.element !== o.element[0] && (i.left = t.left + o.elementPos.left, i.right = t.right + o.elementPos.left, i.top = t.top + o.elementPos.top, i.bottom = t.bottom + o.elementPos.top, "touch" === n.tolerance ? e = !(i.left > h || i.right < r || i.top > l || i.bottom < a) : "fit" === n.tolerance && (e = i.left > r && i.right < h && i.top > a && i.bottom < l), e ? (t.selected && (o._removeClass(t.$element, "ui-selected"), t.selected = !1), t.unselecting && (o._removeClass(t.$element, "ui-unselecting"), t.unselecting = !1), t.selecting || (o._addClass(t.$element, "ui-selecting"), t.selecting = !0, o._trigger("selecting", s, {
                        selecting: t.element
                    }))) : (t.selecting && ((s.metaKey || s.ctrlKey) && t.startselected ? (o._removeClass(t.$element, "ui-selecting"), t.selecting = !1, o._addClass(t.$element, "ui-selected"), t.selected = !0) : (o._removeClass(t.$element, "ui-selecting"), t.selecting = !1, t.startselected && (o._addClass(t.$element, "ui-unselecting"), t.unselecting = !0), o._trigger("unselecting", s, {
                        unselecting: t.element
                    }))), t.selected && (s.metaKey || s.ctrlKey || t.startselected || (o._removeClass(t.$element, "ui-selected"), t.selected = !1, o._addClass(t.$element, "ui-unselecting"), t.unselecting = !0, o._trigger("unselecting", s, {
                        unselecting: t.element
                    })))))
                }), !1
            }
        },
        _mouseStop: function(e) {
            var i = this;
            return this.dragged = !1, x(".ui-unselecting", this.element[0]).each(function() {
                var t = x.data(this, "selectable-item");
                i._removeClass(t.$element, "ui-unselecting"), t.unselecting = !1, t.startselected = !1, i._trigger("unselected", e, {
                    unselected: t.element
                })
            }), x(".ui-selecting", this.element[0]).each(function() {
                var t = x.data(this, "selectable-item");
                i._removeClass(t.$element, "ui-selecting")._addClass(t.$element, "ui-selected"), t.selecting = !1, t.selected = !0, t.startselected = !0, i._trigger("selected", e, {
                    selected: t.element
                })
            }), this._trigger("stop", e), this.helper.remove(), !1
        }
    }), x.widget("ui.sortable", x.ui.mouse, {
        version: "1.13.2",
        widgetEventPrefix: "sort",
        ready: !1,
        options: {
            appendTo: "parent",
            axis: !1,
            connectWith: !1,
            containment: !1,
            cursor: "auto",
            cursorAt: !1,
            dropOnEmpty: !0,
            forcePlaceholderSize: !1,
            forceHelperSize: !1,
            grid: !1,
            handle: !1,
            helper: "original",
            items: "> *",
            opacity: !1,
            placeholder: !1,
            revert: !1,
            scroll: !0,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            scope: "default",
            tolerance: "intersect",
            zIndex: 1e3,
            activate: null,
            beforeStop: null,
            change: null,
            deactivate: null,
            out: null,
            over: null,
            receive: null,
            remove: null,
            sort: null,
            start: null,
            stop: null,
            update: null
        },
        _isOverAxis: function(t, e, i) {
            return e <= t && t < e + i
        },
        _isFloating: function(t) {
            return /left|right/.test(t.css("float")) || /inline|table-cell/.test(t.css("display"))
        },
        _create: function() {
            this.containerCache = {}, this._addClass("ui-sortable"), this.refresh(), this.offset = this.element.offset(), this._mouseInit(), this._setHandleClassName(), this.ready = !0
        },
        _setOption: function(t, e) {
            this._super(t, e), "handle" === t && this._setHandleClassName()
        },
        _setHandleClassName: function() {
            var t = this;
            this._removeClass(this.element.find(".ui-sortable-handle"), "ui-sortable-handle"), x.each(this.items, function() {
                t._addClass(this.instance.options.handle ? this.item.find(this.instance.options.handle) : this.item, "ui-sortable-handle")
            })
        },
        _destroy: function() {
            this._mouseDestroy();
            for (var t = this.items.length - 1; 0 <= t; t--) this.items[t].item.removeData(this.widgetName + "-item");
            return this
        },
        _mouseCapture: function(t, e) {
            var i = null,
                s = !1,
                o = this;
            return !this.reverting && (!this.options.disabled && "static" !== this.options.type && (this._refreshItems(t), x(t.target).parents().each(function() {
                if (x.data(this, o.widgetName + "-item") === o) return i = x(this), !1
            }), !!(i = x.data(t.target, o.widgetName + "-item") === o ? x(t.target) : i) && (!(this.options.handle && !e && (x(this.options.handle, i).find("*").addBack().each(function() {
                this === t.target && (s = !0)
            }), !s)) && (this.currentItem = i, this._removeCurrentsFromItems(), !0))))
        },
        _mouseStart: function(t, e, i) {
            var s, o, n = this.options;
            if ((this.currentContainer = this).refreshPositions(), this.appendTo = x("parent" !== n.appendTo ? n.appendTo : this.currentItem.parent()), this.helper = this._createHelper(t), this._cacheHelperProportions(), this._cacheMargins(), this.offset = this.currentItem.offset(), this.offset = {
                    top: this.offset.top - this.margins.top,
                    left: this.offset.left - this.margins.left
                }, x.extend(this.offset, {
                    click: {
                        left: t.pageX - this.offset.left,
                        top: t.pageY - this.offset.top
                    },
                    relative: this._getRelativeOffset()
                }), this.helper.css("position", "absolute"), this.cssPosition = this.helper.css("position"), n.cursorAt && this._adjustOffsetFromHelper(n.cursorAt), this.domPosition = {
                    prev: this.currentItem.prev()[0],
                    parent: this.currentItem.parent()[0]
                }, this.helper[0] !== this.currentItem[0] && this.currentItem.hide(), this._createPlaceholder(), this.scrollParent = this.placeholder.scrollParent(), x.extend(this.offset, {
                    parent: this._getParentOffset()
                }), n.containment && this._setContainment(), n.cursor && "auto" !== n.cursor && (o = this.document.find("body"), this.storedCursor = o.css("cursor"), o.css("cursor", n.cursor), this.storedStylesheet = x("<style>*{ cursor: " + n.cursor + " !important; }</style>").appendTo(o)), n.zIndex && (this.helper.css("zIndex") && (this._storedZIndex = this.helper.css("zIndex")), this.helper.css("zIndex", n.zIndex)), n.opacity && (this.helper.css("opacity") && (this._storedOpacity = this.helper.css("opacity")), this.helper.css("opacity", n.opacity)), this.scrollParent[0] !== this.document[0] && "HTML" !== this.scrollParent[0].tagName && (this.overflowOffset = this.scrollParent.offset()), this._trigger("start", t, this._uiHash()), this._preserveHelperProportions || this._cacheHelperProportions(), !i)
                for (s = this.containers.length - 1; 0 <= s; s--) this.containers[s]._trigger("activate", t, this._uiHash(this));
            return x.ui.ddmanager && (x.ui.ddmanager.current = this), x.ui.ddmanager && !n.dropBehaviour && x.ui.ddmanager.prepareOffsets(this, t), this.dragging = !0, this._addClass(this.helper, "ui-sortable-helper"), this.helper.parent().is(this.appendTo) || (this.helper.detach().appendTo(this.appendTo), this.offset.parent = this._getParentOffset()), this.position = this.originalPosition = this._generatePosition(t), this.originalPageX = t.pageX, this.originalPageY = t.pageY, this.lastPositionAbs = this.positionAbs = this._convertPositionTo("absolute"), this._mouseDrag(t), !0
        },
        _scroll: function(t) {
            var e = this.options,
                i = !1;
            return this.scrollParent[0] !== this.document[0] && "HTML" !== this.scrollParent[0].tagName ? (this.overflowOffset.top + this.scrollParent[0].offsetHeight - t.pageY < e.scrollSensitivity ? this.scrollParent[0].scrollTop = i = this.scrollParent[0].scrollTop + e.scrollSpeed : t.pageY - this.overflowOffset.top < e.scrollSensitivity && (this.scrollParent[0].scrollTop = i = this.scrollParent[0].scrollTop - e.scrollSpeed), this.overflowOffset.left + this.scrollParent[0].offsetWidth - t.pageX < e.scrollSensitivity ? this.scrollParent[0].scrollLeft = i = this.scrollParent[0].scrollLeft + e.scrollSpeed : t.pageX - this.overflowOffset.left < e.scrollSensitivity && (this.scrollParent[0].scrollLeft = i = this.scrollParent[0].scrollLeft - e.scrollSpeed)) : (t.pageY - this.document.scrollTop() < e.scrollSensitivity ? i = this.document.scrollTop(this.document.scrollTop() - e.scrollSpeed) : this.window.height() - (t.pageY - this.document.scrollTop()) < e.scrollSensitivity && (i = this.document.scrollTop(this.document.scrollTop() + e.scrollSpeed)), t.pageX - this.document.scrollLeft() < e.scrollSensitivity ? i = this.document.scrollLeft(this.document.scrollLeft() - e.scrollSpeed) : this.window.width() - (t.pageX - this.document.scrollLeft()) < e.scrollSensitivity && (i = this.document.scrollLeft(this.document.scrollLeft() + e.scrollSpeed))), i
        },
        _mouseDrag: function(t) {
            var e, i, s, o, n = this.options;
            for (this.position = this._generatePosition(t), this.positionAbs = this._convertPositionTo("absolute"), this.options.axis && "y" === this.options.axis || (this.helper[0].style.left = this.position.left + "px"), this.options.axis && "x" === this.options.axis || (this.helper[0].style.top = this.position.top + "px"), n.scroll && !1 !== this._scroll(t) && (this._refreshItemPositions(!0), x.ui.ddmanager && !n.dropBehaviour && x.ui.ddmanager.prepareOffsets(this, t)), this.dragDirection = {
                    vertical: this._getDragVerticalDirection(),
                    horizontal: this._getDragHorizontalDirection()
                }, e = this.items.length - 1; 0 <= e; e--)
                if (s = (i = this.items[e]).item[0], (o = this._intersectsWithPointer(i)) && i.instance === this.currentContainer && !(s === this.currentItem[0] || this.placeholder[1 === o ? "next" : "prev"]()[0] === s || x.contains(this.placeholder[0], s) || "semi-dynamic" === this.options.type && x.contains(this.element[0], s))) {
                    if (this.direction = 1 === o ? "down" : "up", "pointer" !== this.options.tolerance && !this._intersectsWithSides(i)) break;
                    this._rearrange(t, i), this._trigger("change", t, this._uiHash());
                    break
                } return this._contactContainers(t), x.ui.ddmanager && x.ui.ddmanager.drag(this, t), this._trigger("sort", t, this._uiHash()), this.lastPositionAbs = this.positionAbs, !1
        },
        _mouseStop: function(t, e) {
            var i, s, o, n;
            if (t) return x.ui.ddmanager && !this.options.dropBehaviour && x.ui.ddmanager.drop(this, t), this.options.revert ? (s = (i = this).placeholder.offset(), n = {}, (o = this.options.axis) && "x" !== o || (n.left = s.left - this.offset.parent.left - this.margins.left + (this.offsetParent[0] === this.document[0].body ? 0 : this.offsetParent[0].scrollLeft)), o && "y" !== o || (n.top = s.top - this.offset.parent.top - this.margins.top + (this.offsetParent[0] === this.document[0].body ? 0 : this.offsetParent[0].scrollTop)), this.reverting = !0, x(this.helper).animate(n, parseInt(this.options.revert, 10) || 500, function() {
                i._clear(t)
            })) : this._clear(t, e), !1
        },
        cancel: function() {
            if (this.dragging) {
                this._mouseUp(new x.Event("mouseup", {
                    target: null
                })), "original" === this.options.helper ? (this.currentItem.css(this._storedCSS), this._removeClass(this.currentItem, "ui-sortable-helper")) : this.currentItem.show();
                for (var t = this.containers.length - 1; 0 <= t; t--) this.containers[t]._trigger("deactivate", null, this._uiHash(this)), this.containers[t].containerCache.over && (this.containers[t]._trigger("out", null, this._uiHash(this)), this.containers[t].containerCache.over = 0)
            }
            return this.placeholder && (this.placeholder[0].parentNode && this.placeholder[0].parentNode.removeChild(this.placeholder[0]), "original" !== this.options.helper && this.helper && this.helper[0].parentNode && this.helper.remove(), x.extend(this, {
                helper: null,
                dragging: !1,
                reverting: !1,
                _noFinalSort: null
            }), this.domPosition.prev ? x(this.domPosition.prev).after(this.currentItem) : x(this.domPosition.parent).prepend(this.currentItem)), this
        },
        serialize: function(e) {
            var t = this._getItemsAsjQuery(e && e.connected),
                i = [];
            return e = e || {}, x(t).each(function() {
                var t = (x(e.item || this).attr(e.attribute || "id") || "").match(e.expression || /(.+)[\-=_](.+)/);
                t && i.push((e.key || t[1] + "[]") + "=" + (e.key && e.expression ? t[1] : t[2]))
            }), !i.length && e.key && i.push(e.key + "="), i.join("&")
        },
        toArray: function(t) {
            var e = this._getItemsAsjQuery(t && t.connected),
                i = [];
            return t = t || {}, e.each(function() {
                i.push(x(t.item || this).attr(t.attribute || "id") || "")
            }), i
        },
        _intersectsWith: function(t) {
            var e = this.positionAbs.left,
                i = e + this.helperProportions.width,
                s = this.positionAbs.top,
                o = s + this.helperProportions.height,
                n = t.left,
                r = n + t.width,
                a = t.top,
                h = a + t.height,
                l = this.offset.click.top,
                c = this.offset.click.left,
                l = "x" === this.options.axis || a < s + l && s + l < h,
                c = "y" === this.options.axis || n < e + c && e + c < r;
            return "pointer" === this.options.tolerance || this.options.forcePointerForContainers || "pointer" !== this.options.tolerance && this.helperProportions[this.floating ? "width" : "height"] > t[this.floating ? "width" : "height"] ? l && c : n < e + this.helperProportions.width / 2 && i - this.helperProportions.width / 2 < r && a < s + this.helperProportions.height / 2 && o - this.helperProportions.height / 2 < h
        },
        _intersectsWithPointer: function(t) {
            var e = "x" === this.options.axis || this._isOverAxis(this.positionAbs.top + this.offset.click.top, t.top, t.height),
                t = "y" === this.options.axis || this._isOverAxis(this.positionAbs.left + this.offset.click.left, t.left, t.width);
            return !(!e || !t) && (e = this.dragDirection.vertical, t = this.dragDirection.horizontal, this.floating ? "right" === t || "down" === e ? 2 : 1 : e && ("down" === e ? 2 : 1))
        },
        _intersectsWithSides: function(t) {
            var e = this._isOverAxis(this.positionAbs.top + this.offset.click.top, t.top + t.height / 2, t.height),
                i = this._isOverAxis(this.positionAbs.left + this.offset.click.left, t.left + t.width / 2, t.width),
                s = this.dragDirection.vertical,
                t = this.dragDirection.horizontal;
            return this.floating && t ? "right" === t && i || "left" === t && !i : s && ("down" === s && e || "up" === s && !e)
        },
        _getDragVerticalDirection: function() {
            var t = this.positionAbs.top - this.lastPositionAbs.top;
            return 0 != t && (0 < t ? "down" : "up")
        },
        _getDragHorizontalDirection: function() {
            var t = this.positionAbs.left - this.lastPositionAbs.left;
            return 0 != t && (0 < t ? "right" : "left")
        },
        refresh: function(t) {
            return this._refreshItems(t), this._setHandleClassName(), this.refreshPositions(), this
        },
        _connectWith: function() {
            var t = this.options;
            return t.connectWith.constructor === String ? [t.connectWith] : t.connectWith
        },
        _getItemsAsjQuery: function(t) {
            var e, i, s, o, n = [],
                r = [],
                a = this._connectWith();
            if (a && t)
                for (e = a.length - 1; 0 <= e; e--)
                    for (i = (s = x(a[e], this.document[0])).length - 1; 0 <= i; i--)(o = x.data(s[i], this.widgetFullName)) && o !== this && !o.options.disabled && r.push(["function" == typeof o.options.items ? o.options.items.call(o.element) : x(o.options.items, o.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), o]);

            function h() {
                n.push(this)
            }
            for (r.push(["function" == typeof this.options.items ? this.options.items.call(this.element, null, {
                    options: this.options,
                    item: this.currentItem
                }) : x(this.options.items, this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), this]), e = r.length - 1; 0 <= e; e--) r[e][0].each(h);
            return x(n)
        },
        _removeCurrentsFromItems: function() {
            var i = this.currentItem.find(":data(" + this.widgetName + "-item)");
            this.items = x.grep(this.items, function(t) {
                for (var e = 0; e < i.length; e++)
                    if (i[e] === t.item[0]) return !1;
                return !0
            })
        },
        _refreshItems: function(t) {
            this.items = [], this.containers = [this];
            var e, i, s, o, n, r, a, h, l = this.items,
                c = [
                    ["function" == typeof this.options.items ? this.options.items.call(this.element[0], t, {
                        item: this.currentItem
                    }) : x(this.options.items, this.element), this]
                ],
                p = this._connectWith();
            if (p && this.ready)
                for (e = p.length - 1; 0 <= e; e--)
                    for (i = (s = x(p[e], this.document[0])).length - 1; 0 <= i; i--)(o = x.data(s[i], this.widgetFullName)) && o !== this && !o.options.disabled && (c.push(["function" == typeof o.options.items ? o.options.items.call(o.element[0], t, {
                        item: this.currentItem
                    }) : x(o.options.items, o.element), o]), this.containers.push(o));
            for (e = c.length - 1; 0 <= e; e--)
                for (n = c[e][1], h = (r = c[e][i = 0]).length; i < h; i++)(a = x(r[i])).data(this.widgetName + "-item", n), l.push({
                    item: a,
                    instance: n,
                    width: 0,
                    height: 0,
                    left: 0,
                    top: 0
                })
        },
        _refreshItemPositions: function(t) {
            for (var e, i, s = this.items.length - 1; 0 <= s; s--) e = this.items[s], this.currentContainer && e.instance !== this.currentContainer && e.item[0] !== this.currentItem[0] || (i = this.options.toleranceElement ? x(this.options.toleranceElement, e.item) : e.item, t || (e.width = i.outerWidth(), e.height = i.outerHeight()), i = i.offset(), e.left = i.left, e.top = i.top)
        },
        refreshPositions: function(t) {
            var e, i;
            if (this.floating = !!this.items.length && ("x" === this.options.axis || this._isFloating(this.items[0].item)), this.offsetParent && this.helper && (this.offset.parent = this._getParentOffset()), this._refreshItemPositions(t), this.options.custom && this.options.custom.refreshContainers) this.options.custom.refreshContainers.call(this);
            else
                for (e = this.containers.length - 1; 0 <= e; e--) i = this.containers[e].element.offset(), this.containers[e].containerCache.left = i.left, this.containers[e].containerCache.top = i.top, this.containers[e].containerCache.width = this.containers[e].element.outerWidth(), this.containers[e].containerCache.height = this.containers[e].element.outerHeight();
            return this
        },
        _createPlaceholder: function(i) {
            var s, o, n = (i = i || this).options;
            n.placeholder && n.placeholder.constructor !== String || (s = n.placeholder, o = i.currentItem[0].nodeName.toLowerCase(), n.placeholder = {
                element: function() {
                    var t = x("<" + o + ">", i.document[0]);
                    return i._addClass(t, "ui-sortable-placeholder", s || i.currentItem[0].className)._removeClass(t, "ui-sortable-helper"), "tbody" === o ? i._createTrPlaceholder(i.currentItem.find("tr").eq(0), x("<tr>", i.document[0]).appendTo(t)) : "tr" === o ? i._createTrPlaceholder(i.currentItem, t) : "img" === o && t.attr("src", i.currentItem.attr("src")), s || t.css("visibility", "hidden"), t
                },
                update: function(t, e) {
                    s && !n.forcePlaceholderSize || (e.height() && (!n.forcePlaceholderSize || "tbody" !== o && "tr" !== o) || e.height(i.currentItem.innerHeight() - parseInt(i.currentItem.css("paddingTop") || 0, 10) - parseInt(i.currentItem.css("paddingBottom") || 0, 10)), e.width() || e.width(i.currentItem.innerWidth() - parseInt(i.currentItem.css("paddingLeft") || 0, 10) - parseInt(i.currentItem.css("paddingRight") || 0, 10)))
                }
            }), i.placeholder = x(n.placeholder.element.call(i.element, i.currentItem)), i.currentItem.after(i.placeholder), n.placeholder.update(i, i.placeholder)
        },
        _createTrPlaceholder: function(t, e) {
            var i = this;
            t.children().each(function() {
                x("<td>&#160;</td>", i.document[0]).attr("colspan", x(this).attr("colspan") || 1).appendTo(e)
            })
        },
        _contactContainers: function(t) {
            for (var e, i, s, o, n, r, a, h, l, c = null, p = null, f = this.containers.length - 1; 0 <= f; f--) x.contains(this.currentItem[0], this.containers[f].element[0]) || (this._intersectsWith(this.containers[f].containerCache) ? c && x.contains(this.containers[f].element[0], c.element[0]) || (c = this.containers[f], p = f) : this.containers[f].containerCache.over && (this.containers[f]._trigger("out", t, this._uiHash(this)), this.containers[f].containerCache.over = 0));
            if (c)
                if (1 === this.containers.length) this.containers[p].containerCache.over || (this.containers[p]._trigger("over", t, this._uiHash(this)), this.containers[p].containerCache.over = 1);
                else {
                    for (i = 1e4, s = null, o = (h = c.floating || this._isFloating(this.currentItem)) ? "left" : "top", n = h ? "width" : "height", l = h ? "pageX" : "pageY", e = this.items.length - 1; 0 <= e; e--) x.contains(this.containers[p].element[0], this.items[e].item[0]) && this.items[e].item[0] !== this.currentItem[0] && (r = this.items[e].item.offset()[o], a = !1, t[l] - r > this.items[e][n] / 2 && (a = !0), Math.abs(t[l] - r) < i && (i = Math.abs(t[l] - r), s = this.items[e], this.direction = a ? "up" : "down"));
                    (s || this.options.dropOnEmpty) && (this.currentContainer !== this.containers[p] ? (s ? this._rearrange(t, s, null, !0) : this._rearrange(t, null, this.containers[p].element, !0), this._trigger("change", t, this._uiHash()), this.containers[p]._trigger("change", t, this._uiHash(this)), this.currentContainer = this.containers[p], this.options.placeholder.update(this.currentContainer, this.placeholder), this.scrollParent = this.placeholder.scrollParent(), this.scrollParent[0] !== this.document[0] && "HTML" !== this.scrollParent[0].tagName && (this.overflowOffset = this.scrollParent.offset()), this.containers[p]._trigger("over", t, this._uiHash(this)), this.containers[p].containerCache.over = 1) : this.currentContainer.containerCache.over || (this.containers[p]._trigger("over", t, this._uiHash()), this.currentContainer.containerCache.over = 1))
                }
        },
        _createHelper: function(t) {
            var e = this.options,
                t = "function" == typeof e.helper ? x(e.helper.apply(this.element[0], [t, this.currentItem])) : "clone" === e.helper ? this.currentItem.clone() : this.currentItem;
            return t.parents("body").length || this.appendTo[0].appendChild(t[0]), t[0] === this.currentItem[0] && (this._storedCSS = {
                width: this.currentItem[0].style.width,
                height: this.currentItem[0].style.height,
                position: this.currentItem.css("position"),
                top: this.currentItem.css("top"),
                left: this.currentItem.css("left")
            }), t[0].style.width && !e.forceHelperSize || t.width(this.currentItem.width()), t[0].style.height && !e.forceHelperSize || t.height(this.currentItem.height()), t
        },
        _adjustOffsetFromHelper: function(t) {
            "string" == typeof t && (t = t.split(" ")), "left" in (t = Array.isArray(t) ? {
                left: +t[0],
                top: +t[1] || 0
            } : t) && (this.offset.click.left = t.left + this.margins.left), "right" in t && (this.offset.click.left = this.helperProportions.width - t.right + this.margins.left), "top" in t && (this.offset.click.top = t.top + this.margins.top), "bottom" in t && (this.offset.click.top = this.helperProportions.height - t.bottom + this.margins.top)
        },
        _getParentOffset: function() {
            this.offsetParent = this.helper.offsetParent();
            var t = this.offsetParent.offset();
            return "absolute" === this.cssPosition && this.scrollParent[0] !== this.document[0] && x.contains(this.scrollParent[0], this.offsetParent[0]) && (t.left += this.scrollParent.scrollLeft(), t.top += this.scrollParent.scrollTop()), {
                top: (t = this.offsetParent[0] === this.document[0].body || this.offsetParent[0].tagName && "html" === this.offsetParent[0].tagName.toLowerCase() && x.ui.ie ? {
                    top: 0,
                    left: 0
                } : t).top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: t.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            }
        },
        _getRelativeOffset: function() {
            if ("relative" !== this.cssPosition) return {
                top: 0,
                left: 0
            };
            var t = this.currentItem.position();
            return {
                top: t.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
                left: t.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
            }
        },
        _cacheMargins: function() {
            this.margins = {
                left: parseInt(this.currentItem.css("marginLeft"), 10) || 0,
                top: parseInt(this.currentItem.css("marginTop"), 10) || 0
            }
        },
        _cacheHelperProportions: function() {
            this.helperProportions = {
                width: this.helper.outerWidth(),
                height: this.helper.outerHeight()
            }
        },
        _setContainment: function() {
            var t, e, i = this.options;
            "parent" === i.containment && (i.containment = this.helper[0].parentNode), "document" !== i.containment && "window" !== i.containment || (this.containment = [0 - this.offset.relative.left - this.offset.parent.left, 0 - this.offset.relative.top - this.offset.parent.top, "document" === i.containment ? this.document.width() : this.window.width() - this.helperProportions.width - this.margins.left, ("document" === i.containment ? this.document.height() || document.body.parentNode.scrollHeight : this.window.height() || this.document[0].body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top]), /^(document|window|parent)$/.test(i.containment) || (t = x(i.containment)[0], e = x(i.containment).offset(), i = "hidden" !== x(t).css("overflow"), this.containment = [e.left + (parseInt(x(t).css("borderLeftWidth"), 10) || 0) + (parseInt(x(t).css("paddingLeft"), 10) || 0) - this.margins.left, e.top + (parseInt(x(t).css("borderTopWidth"), 10) || 0) + (parseInt(x(t).css("paddingTop"), 10) || 0) - this.margins.top, e.left + (i ? Math.max(t.scrollWidth, t.offsetWidth) : t.offsetWidth) - (parseInt(x(t).css("borderLeftWidth"), 10) || 0) - (parseInt(x(t).css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left, e.top + (i ? Math.max(t.scrollHeight, t.offsetHeight) : t.offsetHeight) - (parseInt(x(t).css("borderTopWidth"), 10) || 0) - (parseInt(x(t).css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top])
        },
        _convertPositionTo: function(t, e) {
            e = e || this.position;
            var i = "absolute" === t ? 1 : -1,
                s = "absolute" !== this.cssPosition || this.scrollParent[0] !== this.document[0] && x.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent,
                t = /(html|body)/i.test(s[0].tagName);
            return {
                top: e.top + this.offset.relative.top * i + this.offset.parent.top * i - ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : t ? 0 : s.scrollTop()) * i,
                left: e.left + this.offset.relative.left * i + this.offset.parent.left * i - ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : t ? 0 : s.scrollLeft()) * i
            }
        },
        _generatePosition: function(t) {
            var e = this.options,
                i = t.pageX,
                s = t.pageY,
                o = "absolute" !== this.cssPosition || this.scrollParent[0] !== this.document[0] && x.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent,
                n = /(html|body)/i.test(o[0].tagName);
            return "relative" !== this.cssPosition || this.scrollParent[0] !== this.document[0] && this.scrollParent[0] !== this.offsetParent[0] || (this.offset.relative = this._getRelativeOffset()), this.originalPosition && (this.containment && (t.pageX - this.offset.click.left < this.containment[0] && (i = this.containment[0] + this.offset.click.left), t.pageY - this.offset.click.top < this.containment[1] && (s = this.containment[1] + this.offset.click.top), t.pageX - this.offset.click.left > this.containment[2] && (i = this.containment[2] + this.offset.click.left), t.pageY - this.offset.click.top > this.containment[3] && (s = this.containment[3] + this.offset.click.top)), e.grid && (t = this.originalPageY + Math.round((s - this.originalPageY) / e.grid[1]) * e.grid[1], s = !this.containment || t - this.offset.click.top >= this.containment[1] && t - this.offset.click.top <= this.containment[3] ? t : t - this.offset.click.top >= this.containment[1] ? t - e.grid[1] : t + e.grid[1], t = this.originalPageX + Math.round((i - this.originalPageX) / e.grid[0]) * e.grid[0], i = !this.containment || t - this.offset.click.left >= this.containment[0] && t - this.offset.click.left <= this.containment[2] ? t : t - this.offset.click.left >= this.containment[0] ? t - e.grid[0] : t + e.grid[0])), {
                top: s - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : n ? 0 : o.scrollTop()),
                left: i - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : n ? 0 : o.scrollLeft())
            }
        },
        _rearrange: function(t, e, i, s) {
            i ? i[0].appendChild(this.placeholder[0]) : e.item[0].parentNode.insertBefore(this.placeholder[0], "down" === this.direction ? e.item[0] : e.item[0].nextSibling), this.counter = this.counter ? ++this.counter : 1;
            var o = this.counter;
            this._delay(function() {
                o === this.counter && this.refreshPositions(!s)
            })
        },
        _clear: function(t, e) {
            this.reverting = !1;
            var i, s = [];
            if (!this._noFinalSort && this.currentItem.parent().length && this.placeholder.before(this.currentItem), this._noFinalSort = null, this.helper[0] === this.currentItem[0]) {
                for (i in this._storedCSS) "auto" !== this._storedCSS[i] && "static" !== this._storedCSS[i] || (this._storedCSS[i] = "");
                this.currentItem.css(this._storedCSS), this._removeClass(this.currentItem, "ui-sortable-helper")
            } else this.currentItem.show();

            function o(e, i, s) {
                return function(t) {
                    s._trigger(e, t, i._uiHash(i))
                }
            }
            for (this.fromOutside && !e && s.push(function(t) {
                    this._trigger("receive", t, this._uiHash(this.fromOutside))
                }), !this.fromOutside && this.domPosition.prev === this.currentItem.prev().not(".ui-sortable-helper")[0] && this.domPosition.parent === this.currentItem.parent()[0] || e || s.push(function(t) {
                    this._trigger("update", t, this._uiHash())
                }), this !== this.currentContainer && (e || (s.push(function(t) {
                    this._trigger("remove", t, this._uiHash())
                }), s.push(function(e) {
                    return function(t) {
                        e._trigger("receive", t, this._uiHash(this))
                    }
                }.call(this, this.currentContainer)), s.push(function(e) {
                    return function(t) {
                        e._trigger("update", t, this._uiHash(this))
                    }
                }.call(this, this.currentContainer)))), i = this.containers.length - 1; 0 <= i; i--) e || s.push(o("deactivate", this, this.containers[i])), this.containers[i].containerCache.over && (s.push(o("out", this, this.containers[i])), this.containers[i].containerCache.over = 0);
            if (this.storedCursor && (this.document.find("body").css("cursor", this.storedCursor), this.storedStylesheet.remove()), this._storedOpacity && this.helper.css("opacity", this._storedOpacity), this._storedZIndex && this.helper.css("zIndex", "auto" === this._storedZIndex ? "" : this._storedZIndex), this.dragging = !1, e || this._trigger("beforeStop", t, this._uiHash()), this.placeholder[0].parentNode.removeChild(this.placeholder[0]), this.cancelHelperRemoval || (this.helper[0] !== this.currentItem[0] && this.helper.remove(), this.helper = null), !e) {
                for (i = 0; i < s.length; i++) s[i].call(this, t);
                this._trigger("stop", t, this._uiHash())
            }
            return this.fromOutside = !1, !this.cancelHelperRemoval
        },
        _trigger: function() {
            !1 === x.Widget.prototype._trigger.apply(this, arguments) && this.cancel()
        },
        _uiHash: function(t) {
            var e = t || this;
            return {
                helper: e.helper,
                placeholder: e.placeholder || x([]),
                position: e.position,
                originalPosition: e.originalPosition,
                offset: e.positionAbs,
                item: e.currentItem,
                sender: t ? t.element : null
            }
        }
    });
    var m = x,
        v = {},
        _ = v.toString,
        b = /^([\-+])=\s*(\d+\.?\d*)/,
        w = [{
            re: /rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
            parse: function(t) {
                return [t[1], t[2], t[3], t[4]]
            }
        }, {
            re: /rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
            parse: function(t) {
                return [2.55 * t[1], 2.55 * t[2], 2.55 * t[3], t[4]]
            }
        }, {
            re: /#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})?/,
            parse: function(t) {
                return [parseInt(t[1], 16), parseInt(t[2], 16), parseInt(t[3], 16), t[4] ? (parseInt(t[4], 16) / 255).toFixed(2) : 1]
            }
        }, {
            re: /#([a-f0-9])([a-f0-9])([a-f0-9])([a-f0-9])?/,
            parse: function(t) {
                return [parseInt(t[1] + t[1], 16), parseInt(t[2] + t[2], 16), parseInt(t[3] + t[3], 16), t[4] ? (parseInt(t[4] + t[4], 16) / 255).toFixed(2) : 1]
            }
        }, {
            re: /hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
            space: "hsla",
            parse: function(t) {
                return [t[1], t[2] / 100, t[3] / 100, t[4]]
            }
        }],
        y = m.Color = function(t, e, i, s) {
            return new m.Color.fn.parse(t, e, i, s)
        },
        T = {
            rgba: {
                props: {
                    red: {
                        idx: 0,
                        type: "byte"
                    },
                    green: {
                        idx: 1,
                        type: "byte"
                    },
                    blue: {
                        idx: 2,
                        type: "byte"
                    }
                }
            },
            hsla: {
                props: {
                    hue: {
                        idx: 0,
                        type: "degrees"
                    },
                    saturation: {
                        idx: 1,
                        type: "percent"
                    },
                    lightness: {
                        idx: 2,
                        type: "percent"
                    }
                }
            }
        },
        W = {
            byte: {
                floor: !0,
                max: 255
            },
            percent: {
                max: 1
            },
            degrees: {
                mod: 360,
                floor: !0
            }
        },
        k = y.support = {},
        D = m("<p>")[0],
        N = m.each;

    function E(t) {
        return null == t ? t + "" : "object" == typeof t ? v[_.call(t)] || "object" : typeof t
    }

    function O(t, e, i) {
        var s = W[e.type] || {};
        return null == t ? i || !e.def ? null : e.def : (t = s.floor ? ~~t : parseFloat(t), isNaN(t) ? e.def : s.mod ? (t + s.mod) % s.mod : Math.min(s.max, Math.max(0, t)))
    }

    function R(s) {
        var o = y(),
            n = o._rgba = [];
        return s = s.toLowerCase(), N(w, function(t, e) {
            var i = e.re.exec(s),
                i = i && e.parse(i),
                e = e.space || "rgba";
            if (i) return i = o[e](i), o[T[e].cache] = i[T[e].cache], n = o._rgba = i._rgba, !1
        }), n.length ? ("0,0,0,0" === n.join() && m.extend(n, K.transparent), o) : K[s]
    }

    function M(t, e, i) {
        return 6 * (i = (i + 1) % 1) < 1 ? t + (e - t) * i * 6 : 2 * i < 1 ? e : 3 * i < 2 ? t + (e - t) * (2 / 3 - i) * 6 : t
    }
    D.style.cssText = "background-color:rgba(1,1,1,.5)", k.rgba = -1 < D.style.backgroundColor.indexOf("rgba"), N(T, function(t, e) {
        e.cache = "_" + t, e.props.alpha = {
            idx: 3,
            type: "percent",
            def: 1
        }
    }), m.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), function(t, e) {
        v["[object " + e + "]"] = e.toLowerCase()
    }), (y.fn = m.extend(y.prototype, {
        parse: function(o, t, e, i) {
            if (void 0 === o) return this._rgba = [null, null, null, null], this;
            (o.jquery || o.nodeType) && (o = m(o).css(t), t = void 0);
            var n = this,
                s = E(o),
                r = this._rgba = [];
            return void 0 !== t && (o = [o, t, e, i], s = "array"), "string" === s ? this.parse(R(o) || K._default) : "array" === s ? (N(T.rgba.props, function(t, e) {
                r[e.idx] = O(o[e.idx], e)
            }), this) : "object" === s ? (N(T, o instanceof y ? function(t, e) {
                o[e.cache] && (n[e.cache] = o[e.cache].slice())
            } : function(t, i) {
                var s = i.cache;
                N(i.props, function(t, e) {
                    if (!n[s] && i.to) {
                        if ("alpha" === t || null == o[t]) return;
                        n[s] = i.to(n._rgba)
                    }
                    n[s][e.idx] = O(o[t], e, !0)
                }), n[s] && m.inArray(null, n[s].slice(0, 3)) < 0 && (null == n[s][3] && (n[s][3] = 1), i.from && (n._rgba = i.from(n[s])))
            }), this) : void 0
        },
        is: function(t) {
            var o = y(t),
                n = !0,
                r = this;
            return N(T, function(t, e) {
                var i, s = o[e.cache];
                return s && (i = r[e.cache] || e.to && e.to(r._rgba) || [], N(e.props, function(t, e) {
                    if (null != s[e.idx]) return n = s[e.idx] === i[e.idx]
                })), n
            }), n
        },
        _space: function() {
            var i = [],
                s = this;
            return N(T, function(t, e) {
                s[e.cache] && i.push(t)
            }), i.pop()
        },
        transition: function(t, r) {
            var e = (l = y(t))._space(),
                i = T[e],
                t = 0 === this.alpha() ? y("transparent") : this,
                a = t[i.cache] || i.to(t._rgba),
                h = a.slice(),
                l = l[i.cache];
            return N(i.props, function(t, e) {
                var i = e.idx,
                    s = a[i],
                    o = l[i],
                    n = W[e.type] || {};
                null !== o && (null === s ? h[i] = o : (n.mod && (o - s > n.mod / 2 ? s += n.mod : s - o > n.mod / 2 && (s -= n.mod)), h[i] = O((o - s) * r + s, e)))
            }), this[e](h)
        },
        blend: function(t) {
            if (1 === this._rgba[3]) return this;
            var e = this._rgba.slice(),
                i = e.pop(),
                s = y(t)._rgba;
            return y(m.map(e, function(t, e) {
                return (1 - i) * s[e] + i * t
            }))
        },
        toRgbaString: function() {
            var t = "rgba(",
                e = m.map(this._rgba, function(t, e) {
                    return null != t ? t : 2 < e ? 1 : 0
                });
            return 1 === e[3] && (e.pop(), t = "rgb("), t + e.join() + ")"
        },
        toHslaString: function() {
            var t = "hsla(",
                e = m.map(this.hsla(), function(t, e) {
                    return null == t && (t = 2 < e ? 1 : 0), t = e && e < 3 ? Math.round(100 * t) + "%" : t
                });
            return 1 === e[3] && (e.pop(), t = "hsl("), t + e.join() + ")"
        },
        toHexString: function(t) {
            var e = this._rgba.slice(),
                i = e.pop();
            return t && e.push(~~(255 * i)), "#" + m.map(e, function(t) {
                return 1 === (t = (t || 0).toString(16)).length ? "0" + t : t
            }).join("")
        },
        toString: function() {
            return 0 === this._rgba[3] ? "transparent" : this.toRgbaString()
        }
    })).parse.prototype = y.fn, T.hsla.to = function(t) {
        if (null == t[0] || null == t[1] || null == t[2]) return [null, null, null, t[3]];
        var e = t[0] / 255,
            i = t[1] / 255,
            s = t[2] / 255,
            o = t[3],
            n = Math.max(e, i, s),
            r = Math.min(e, i, s),
            a = n - r,
            h = n + r,
            t = .5 * h,
            i = r === n ? 0 : e === n ? 60 * (i - s) / a + 360 : i === n ? 60 * (s - e) / a + 120 : 60 * (e - i) / a + 240,
            h = 0 == a ? 0 : t <= .5 ? a / h : a / (2 - h);
        return [Math.round(i) % 360, h, t, null == o ? 1 : o]
    }, T.hsla.from = function(t) {
        if (null == t[0] || null == t[1] || null == t[2]) return [null, null, null, t[3]];
        var e = t[0] / 360,
            i = t[1],
            s = t[2],
            t = t[3],
            i = s <= .5 ? s * (1 + i) : s + i - s * i,
            s = 2 * s - i;
        return [Math.round(255 * M(s, i, e + 1 / 3)), Math.round(255 * M(s, i, e)), Math.round(255 * M(s, i, e - 1 / 3)), t]
    }, N(T, function(h, t) {
        var e = t.props,
            n = t.cache,
            r = t.to,
            a = t.from;
        y.fn[h] = function(t) {
            if (r && !this[n] && (this[n] = r(this._rgba)), void 0 === t) return this[n].slice();
            var i = E(t),
                s = "array" === i || "object" === i ? t : arguments,
                o = this[n].slice();
            return N(e, function(t, e) {
                t = s["object" === i ? t : e.idx];
                null == t && (t = o[e.idx]), o[e.idx] = O(t, e)
            }), a ? ((t = y(a(o)))[n] = o, t) : y(o)
        }, N(e, function(r, a) {
            y.fn[r] || (y.fn[r] = function(t) {
                var e, i = E(t),
                    s = "alpha" === r ? this._hsla ? "hsla" : "rgba" : h,
                    o = this[s](),
                    n = o[a.idx];
                return "undefined" === i ? n : ("function" === i && (i = E(t = t.call(this, n))), null == t && a.empty ? this : ("string" === i && (e = b.exec(t)) && (t = n + parseFloat(e[2]) * ("+" === e[1] ? 1 : -1)), o[a.idx] = t, this[s](o)))
            })
        })
    }), (y.hook = function(t) {
        t = t.split(" ");
        N(t, function(t, n) {
            m.cssHooks[n] = {
                set: function(t, e) {
                    var i, s, o = "";
                    if ("transparent" !== e && ("string" !== E(e) || (i = R(e)))) {
                        if (e = y(i || e), !k.rgba && 1 !== e._rgba[3]) {
                            for (s = "backgroundColor" === n ? t.parentNode : t;
                                ("" === o || "transparent" === o) && s && s.style;) try {
                                o = m.css(s, "backgroundColor"), s = s.parentNode
                            } catch (t) {}
                            e = e.blend(o && "transparent" !== o ? o : "_default")
                        }
                        e = e.toRgbaString()
                    }
                    try {
                        t.style[n] = e
                    } catch (t) {}
                }
            }, m.fx.step[n] = function(t) {
                t.colorInit || (t.start = y(t.elem, n), t.end = y(t.end), t.colorInit = !0), m.cssHooks[n].set(t.elem, t.start.transition(t.end, t.pos))
            }
        })
    })("backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor"), m.cssHooks.borderColor = {
        expand: function(i) {
            var s = {};
            return N(["Top", "Right", "Bottom", "Left"], function(t, e) {
                s["border" + e + "Color"] = i
            }), s
        }
    };
    var L, A, B, F, q, j, X, Y, $, U, K = m.Color.names = {
            aqua: "#00ffff",
            black: "#000000",
            blue: "#0000ff",
            fuchsia: "#ff00ff",
            gray: "#808080",
            green: "#008000",
            lime: "#00ff00",
            maroon: "#800000",
            navy: "#000080",
            olive: "#808000",
            purple: "#800080",
            red: "#ff0000",
            silver: "#c0c0c0",
            teal: "#008080",
            white: "#ffffff",
            yellow: "#ffff00",
            transparent: [null, null, null, 0],
            _default: "#ffffff"
        },
        Q = "ui-effects-",
        V = "ui-effects-style",
        Z = "ui-effects-animated";

    function G(t) {
        var e, i, s = t.ownerDocument.defaultView ? t.ownerDocument.defaultView.getComputedStyle(t, null) : t.currentStyle,
            o = {};
        if (s && s.length && s[0] && s[s[0]])
            for (i = s.length; i--;) "string" == typeof s[e = s[i]] && (o[e.replace(/-([\da-z])/gi, function(t, e) {
                return e.toUpperCase()
            })] = s[e]);
        else
            for (e in s) "string" == typeof s[e] && (o[e] = s[e]);
        return o
    }

    function J(t, e, i, s) {
        return t = {
            effect: t = x.isPlainObject(t) ? (e = t).effect : t
        }, "function" == typeof(e = null == e ? {} : e) && (s = e, i = null, e = {}), "number" != typeof e && !x.fx.speeds[e] || (s = i, i = e, e = {}), "function" == typeof i && (s = i, i = null), e && x.extend(t, e), i = i || e.duration, t.duration = x.fx.off ? 0 : "number" == typeof i ? i : i in x.fx.speeds ? x.fx.speeds[i] : x.fx.speeds._default, t.complete = s || e.complete, t
    }

    function tt(t) {
        return !t || "number" == typeof t || x.fx.speeds[t] || ("string" == typeof t && !x.effects.effect[t] || ("function" == typeof t || "object" == typeof t && !t.effect))
    }

    function et(t, e) {
        var i = e.outerWidth(),
            e = e.outerHeight(),
            t = /^rect\((-?\d*\.?\d*px|-?\d+%|auto),?\s*(-?\d*\.?\d*px|-?\d+%|auto),?\s*(-?\d*\.?\d*px|-?\d+%|auto),?\s*(-?\d*\.?\d*px|-?\d+%|auto)\)$/.exec(t) || ["", 0, i, e, 0];
        return {
            top: parseFloat(t[1]) || 0,
            right: "auto" === t[2] ? i : parseFloat(t[2]),
            bottom: "auto" === t[3] ? e : parseFloat(t[3]),
            left: parseFloat(t[4]) || 0
        }
    }
    x.effects = {
        effect: {}
    }, F = ["add", "remove", "toggle"], q = {
        border: 1,
        borderBottom: 1,
        borderColor: 1,
        borderLeft: 1,
        borderRight: 1,
        borderTop: 1,
        borderWidth: 1,
        margin: 1,
        padding: 1
    }, x.each(["borderLeftStyle", "borderRightStyle", "borderBottomStyle", "borderTopStyle"], function(t, e) {
        x.fx.step[e] = function(t) {
            ("none" !== t.end && !t.setAttr || 1 === t.pos && !t.setAttr) && (m.style(t.elem, e, t.end), t.setAttr = !0)
        }
    }), x.fn.addBack || (x.fn.addBack = function(t) {
        return this.add(null == t ? this.prevObject : this.prevObject.filter(t))
    }), x.effects.animateClass = function(o, t, e, i) {
        var n = x.speed(t, e, i);
        return this.queue(function() {
            var i = x(this),
                t = i.attr("class") || "",
                e = (e = n.children ? i.find("*").addBack() : i).map(function() {
                    return {
                        el: x(this),
                        start: G(this)
                    }
                }),
                s = function() {
                    x.each(F, function(t, e) {
                        o[e] && i[e + "Class"](o[e])
                    })
                };
            s(), e = e.map(function() {
                return this.end = G(this.el[0]), this.diff = function(t, e) {
                    var i, s, o = {};
                    for (i in e) s = e[i], t[i] !== s && (q[i] || !x.fx.step[i] && isNaN(parseFloat(s)) || (o[i] = s));
                    return o
                }(this.start, this.end), this
            }), i.attr("class", t), e = e.map(function() {
                var t = this,
                    e = x.Deferred(),
                    i = x.extend({}, n, {
                        queue: !1,
                        complete: function() {
                            e.resolve(t)
                        }
                    });
                return this.el.animate(this.diff, i), e.promise()
            }), x.when.apply(x, e.get()).done(function() {
                s(), x.each(arguments, function() {
                    var e = this.el;
                    x.each(this.diff, function(t) {
                        e.css(t, "")
                    })
                }), n.complete.call(i[0])
            })
        })
    }, x.fn.extend({
        addClass: (B = x.fn.addClass, function(t, e, i, s) {
            return e ? x.effects.animateClass.call(this, {
                add: t
            }, e, i, s) : B.apply(this, arguments)
        }),
        removeClass: (A = x.fn.removeClass, function(t, e, i, s) {
            return 1 < arguments.length ? x.effects.animateClass.call(this, {
                remove: t
            }, e, i, s) : A.apply(this, arguments)
        }),
        toggleClass: (L = x.fn.toggleClass, function(t, e, i, s, o) {
            return "boolean" == typeof e || void 0 === e ? i ? x.effects.animateClass.call(this, e ? {
                add: t
            } : {
                remove: t
            }, i, s, o) : L.apply(this, arguments) : x.effects.animateClass.call(this, {
                toggle: t
            }, e, i, s)
        }),
        switchClass: function(t, e, i, s, o) {
            return x.effects.animateClass.call(this, {
                add: e,
                remove: t
            }, i, s, o)
        }
    }), x.expr && x.expr.pseudos && x.expr.pseudos.animated && (x.expr.pseudos.animated = (j = x.expr.pseudos.animated, function(t) {
        return !!x(t).data(Z) || j(t)
    })), !1 !== x.uiBackCompat && x.extend(x.effects, {
        save: function(t, e) {
            for (var i = 0, s = e.length; i < s; i++) null !== e[i] && t.data(Q + e[i], t[0].style[e[i]])
        },
        restore: function(t, e) {
            for (var i, s = 0, o = e.length; s < o; s++) null !== e[s] && (i = t.data(Q + e[s]), t.css(e[s], i))
        },
        setMode: function(t, e) {
            return e = "toggle" === e ? t.is(":hidden") ? "show" : "hide" : e
        },
        createWrapper: function(i) {
            if (i.parent().is(".ui-effects-wrapper")) return i.parent();
            var s = {
                    width: i.outerWidth(!0),
                    height: i.outerHeight(!0),
                    float: i.css("float")
                },
                t = x("<div></div>").addClass("ui-effects-wrapper").css({
                    fontSize: "100%",
                    background: "transparent",
                    border: "none",
                    margin: 0,
                    padding: 0
                }),
                e = {
                    width: i.width(),
                    height: i.height()
                },
                o = document.activeElement;
            try {
                o.id
            } catch (t) {
                o = document.body
            }
            return i.wrap(t), i[0] !== o && !x.contains(i[0], o) || x(o).trigger("focus"), t = i.parent(), "static" === i.css("position") ? (t.css({
                position: "relative"
            }), i.css({
                position: "relative"
            })) : (x.extend(s, {
                position: i.css("position"),
                zIndex: i.css("z-index")
            }), x.each(["top", "left", "bottom", "right"], function(t, e) {
                s[e] = i.css(e), isNaN(parseInt(s[e], 10)) && (s[e] = "auto")
            }), i.css({
                position: "relative",
                top: 0,
                left: 0,
                right: "auto",
                bottom: "auto"
            })), i.css(e), t.css(s).show()
        },
        removeWrapper: function(t) {
            var e = document.activeElement;
            return t.parent().is(".ui-effects-wrapper") && (t.parent().replaceWith(t), t[0] !== e && !x.contains(t[0], e) || x(e).trigger("focus")), t
        }
    }), x.extend(x.effects, {
        version: "1.13.2",
        define: function(t, e, i) {
            return i || (i = e, e = "effect"), x.effects.effect[t] = i, x.effects.effect[t].mode = e, i
        },
        scaledDimensions: function(t, e, i) {
            if (0 === e) return {
                height: 0,
                width: 0,
                outerHeight: 0,
                outerWidth: 0
            };
            var s = "horizontal" !== i ? (e || 100) / 100 : 1,
                e = "vertical" !== i ? (e || 100) / 100 : 1;
            return {
                height: t.height() * e,
                width: t.width() * s,
                outerHeight: t.outerHeight() * e,
                outerWidth: t.outerWidth() * s
            }
        },
        clipToBox: function(t) {
            return {
                width: t.clip.right - t.clip.left,
                height: t.clip.bottom - t.clip.top,
                left: t.clip.left,
                top: t.clip.top
            }
        },
        unshift: function(t, e, i) {
            var s = t.queue();
            1 < e && s.splice.apply(s, [1, 0].concat(s.splice(e, i))), t.dequeue()
        },
        saveStyle: function(t) {
            t.data(V, t[0].style.cssText)
        },
        restoreStyle: function(t) {
            t[0].style.cssText = t.data(V) || "", t.removeData(V)
        },
        mode: function(t, e) {
            t = t.is(":hidden");
            return "toggle" === e && (e = t ? "show" : "hide"), e = (t ? "hide" === e : "show" === e) ? "none" : e
        },
        getBaseline: function(t, e) {
            var i, s;
            switch (t[0]) {
                case "top":
                    i = 0;
                    break;
                case "middle":
                    i = .5;
                    break;
                case "bottom":
                    i = 1;
                    break;
                default:
                    i = t[0] / e.height
            }
            switch (t[1]) {
                case "left":
                    s = 0;
                    break;
                case "center":
                    s = .5;
                    break;
                case "right":
                    s = 1;
                    break;
                default:
                    s = t[1] / e.width
            }
            return {
                x: s,
                y: i
            }
        },
        createPlaceholder: function(t) {
            var e, i = t.css("position"),
                s = t.position();
            return t.css({
                marginTop: t.css("marginTop"),
                marginBottom: t.css("marginBottom"),
                marginLeft: t.css("marginLeft"),
                marginRight: t.css("marginRight")
            }).outerWidth(t.outerWidth()).outerHeight(t.outerHeight()), /^(static|relative)/.test(i) && (i = "absolute", e = x("<" + t[0].nodeName + ">").insertAfter(t).css({
                display: /^(inline|ruby)/.test(t.css("display")) ? "inline-block" : "block",
                visibility: "hidden",
                marginTop: t.css("marginTop"),
                marginBottom: t.css("marginBottom"),
                marginLeft: t.css("marginLeft"),
                marginRight: t.css("marginRight"),
                float: t.css("float")
            }).outerWidth(t.outerWidth()).outerHeight(t.outerHeight()).addClass("ui-effects-placeholder"), t.data(Q + "placeholder", e)), t.css({
                position: i,
                left: s.left,
                top: s.top
            }), e
        },
        removePlaceholder: function(t) {
            var e = Q + "placeholder",
                i = t.data(e);
            i && (i.remove(), t.removeData(e))
        },
        cleanUp: function(t) {
            x.effects.restoreStyle(t), x.effects.removePlaceholder(t)
        },
        setTransition: function(s, t, o, n) {
            return n = n || {}, x.each(t, function(t, e) {
                var i = s.cssUnit(e);
                0 < i[0] && (n[e] = i[0] * o + i[1])
            }), n
        }
    }), x.fn.extend({
        effect: function() {
            function t(t) {
                var e = x(this),
                    i = x.effects.mode(e, a) || n;
                e.data(Z, !0), h.push(i), n && ("show" === i || i === n && "hide" === i) && e.show(), n && "none" === i || x.effects.saveStyle(e), "function" == typeof t && t()
            }
            var s = J.apply(this, arguments),
                o = x.effects.effect[s.effect],
                n = o.mode,
                e = s.queue,
                i = e || "fx",
                r = s.complete,
                a = s.mode,
                h = [];
            return x.fx.off || !o ? a ? this[a](s.duration, r) : this.each(function() {
                r && r.call(this)
            }) : !1 === e ? this.each(t).each(l) : this.queue(i, t).queue(i, l);

            function l(t) {
                var e = x(this);

                function i() {
                    "function" == typeof r && r.call(e[0]), "function" == typeof t && t()
                }
                s.mode = h.shift(), !1 === x.uiBackCompat || n ? "none" === s.mode ? (e[a](), i()) : o.call(e[0], s, function() {
                    e.removeData(Z), x.effects.cleanUp(e), "hide" === s.mode && e.hide(), i()
                }) : (e.is(":hidden") ? "hide" === a : "show" === a) ? (e[a](), i()) : o.call(e[0], s, i)
            }
        },
        show: ($ = x.fn.show, function(t) {
            if (tt(t)) return $.apply(this, arguments);
            t = J.apply(this, arguments);
            return t.mode = "show", this.effect.call(this, t)
        }),
        hide: (Y = x.fn.hide, function(t) {
            if (tt(t)) return Y.apply(this, arguments);
            t = J.apply(this, arguments);
            return t.mode = "hide", this.effect.call(this, t)
        }),
        toggle: (X = x.fn.toggle, function(t) {
            if (tt(t) || "boolean" == typeof t) return X.apply(this, arguments);
            t = J.apply(this, arguments);
            return t.mode = "toggle", this.effect.call(this, t)
        }),
        cssUnit: function(t) {
            var i = this.css(t),
                s = [];
            return x.each(["em", "px", "%", "pt"], function(t, e) {
                0 < i.indexOf(e) && (s = [parseFloat(i), e])
            }), s
        },
        cssClip: function(t) {
            return t ? this.css("clip", "rect(" + t.top + "px " + t.right + "px " + t.bottom + "px " + t.left + "px)") : et(this.css("clip"), this)
        },
        transfer: function(t, e) {
            var i = x(this),
                s = x(t.to),
                o = "fixed" === s.css("position"),
                n = x("body"),
                r = o ? n.scrollTop() : 0,
                a = o ? n.scrollLeft() : 0,
                n = s.offset(),
                n = {
                    top: n.top - r,
                    left: n.left - a,
                    height: s.innerHeight(),
                    width: s.innerWidth()
                },
                s = i.offset(),
                h = x("<div class='ui-effects-transfer'></div>");
            h.appendTo("body").addClass(t.className).css({
                top: s.top - r,
                left: s.left - a,
                height: i.innerHeight(),
                width: i.innerWidth(),
                position: o ? "fixed" : "absolute"
            }).animate(n, t.duration, t.easing, function() {
                h.remove(), "function" == typeof e && e()
            })
        }
    }), x.fx.step.clip = function(t) {
        t.clipInit || (t.start = x(t.elem).cssClip(), "string" == typeof t.end && (t.end = et(t.end, t.elem)), t.clipInit = !0), x(t.elem).cssClip({
            top: t.pos * (t.end.top - t.start.top) + t.start.top,
            right: t.pos * (t.end.right - t.start.right) + t.start.right,
            bottom: t.pos * (t.end.bottom - t.start.bottom) + t.start.bottom,
            left: t.pos * (t.end.left - t.start.left) + t.start.left
        })
    }, U = {}, x.each(["Quad", "Cubic", "Quart", "Quint", "Expo"], function(e, t) {
        U[t] = function(t) {
            return Math.pow(t, e + 2)
        }
    }), x.extend(U, {
        Sine: function(t) {
            return 1 - Math.cos(t * Math.PI / 2)
        },
        Circ: function(t) {
            return 1 - Math.sqrt(1 - t * t)
        },
        Elastic: function(t) {
            return 0 === t || 1 === t ? t : -Math.pow(2, 8 * (t - 1)) * Math.sin((80 * (t - 1) - 7.5) * Math.PI / 15)
        },
        Back: function(t) {
            return t * t * (3 * t - 2)
        },
        Bounce: function(t) {
            for (var e, i = 4; t < ((e = Math.pow(2, --i)) - 1) / 11;);
            return 1 / Math.pow(4, 3 - i) - 7.5625 * Math.pow((3 * e - 2) / 22 - t, 2)
        }
    }), x.each(U, function(t, e) {
        x.easing["easeIn" + t] = e, x.easing["easeOut" + t] = function(t) {
            return 1 - e(1 - t)
        }, x.easing["easeInOut" + t] = function(t) {
            return t < .5 ? e(2 * t) / 2 : 1 - e(-2 * t + 2) / 2
        }
    });
    D = x.effects, x.effects.define("blind", "hide", function(t, e) {
        var i = {
                up: ["bottom", "top"],
                vertical: ["bottom", "top"],
                down: ["top", "bottom"],
                left: ["right", "left"],
                horizontal: ["right", "left"],
                right: ["left", "right"]
            },
            s = x(this),
            o = t.direction || "up",
            n = s.cssClip(),
            r = {
                clip: x.extend({}, n)
            },
            a = x.effects.createPlaceholder(s);
        r.clip[i[o][0]] = r.clip[i[o][1]], "show" === t.mode && (s.cssClip(r.clip), a && a.css(x.effects.clipToBox(r)), r.clip = n), a && a.animate(x.effects.clipToBox(r), t.duration, t.easing), s.animate(r, {
            queue: !1,
            duration: t.duration,
            easing: t.easing,
            complete: e
        })
    }), x.effects.define("bounce", function(t, e) {
        var i, s, o = x(this),
            n = t.mode,
            r = "hide" === n,
            a = "show" === n,
            h = t.direction || "up",
            l = t.distance,
            c = t.times || 5,
            n = 2 * c + (a || r ? 1 : 0),
            p = t.duration / n,
            f = t.easing,
            u = "up" === h || "down" === h ? "top" : "left",
            d = "up" === h || "left" === h,
            g = 0,
            t = o.queue().length;
        for (x.effects.createPlaceholder(o), h = o.css(u), l = l || o["top" == u ? "outerHeight" : "outerWidth"]() / 3, a && ((s = {
                opacity: 1
            })[u] = h, o.css("opacity", 0).css(u, d ? 2 * -l : 2 * l).animate(s, p, f)), r && (l /= Math.pow(2, c - 1)), (s = {})[u] = h; g < c; g++)(i = {})[u] = (d ? "-=" : "+=") + l, o.animate(i, p, f).animate(s, p, f), l = r ? 2 * l : l / 2;
        r && ((i = {
            opacity: 0
        })[u] = (d ? "-=" : "+=") + l, o.animate(i, p, f)), o.queue(e), x.effects.unshift(o, t, 1 + n)
    }), x.effects.define("clip", "hide", function(t, e) {
        var i = {},
            s = x(this),
            o = t.direction || "vertical",
            n = "both" === o,
            r = n || "horizontal" === o,
            n = n || "vertical" === o,
            o = s.cssClip();
        i.clip = {
            top: n ? (o.bottom - o.top) / 2 : o.top,
            right: r ? (o.right - o.left) / 2 : o.right,
            bottom: n ? (o.bottom - o.top) / 2 : o.bottom,
            left: r ? (o.right - o.left) / 2 : o.left
        }, x.effects.createPlaceholder(s), "show" === t.mode && (s.cssClip(i.clip), i.clip = o), s.animate(i, {
            queue: !1,
            duration: t.duration,
            easing: t.easing,
            complete: e
        })
    }), x.effects.define("drop", "hide", function(t, e) {
        var i = x(this),
            s = "show" === t.mode,
            o = t.direction || "left",
            n = "up" === o || "down" === o ? "top" : "left",
            r = "up" === o || "left" === o ? "-=" : "+=",
            a = "+=" == r ? "-=" : "+=",
            h = {
                opacity: 0
            };
        x.effects.createPlaceholder(i), o = t.distance || i["top" == n ? "outerHeight" : "outerWidth"](!0) / 2, h[n] = r + o, s && (i.css(h), h[n] = a + o, h.opacity = 1), i.animate(h, {
            queue: !1,
            duration: t.duration,
            easing: t.easing,
            complete: e
        })
    }), x.effects.define("explode", "hide", function(t, e) {
        var i, s, o, n, r, a, h = t.pieces ? Math.round(Math.sqrt(t.pieces)) : 3,
            l = h,
            c = x(this),
            p = "show" === t.mode,
            f = c.show().css("visibility", "hidden").offset(),
            u = Math.ceil(c.outerWidth() / l),
            d = Math.ceil(c.outerHeight() / h),
            g = [];

        function m() {
            g.push(this), g.length === h * l && (c.css({
                visibility: "visible"
            }), x(g).remove(), e())
        }
        for (i = 0; i < h; i++)
            for (n = f.top + i * d, a = i - (h - 1) / 2, s = 0; s < l; s++) o = f.left + s * u, r = s - (l - 1) / 2, c.clone().appendTo("body").wrap("<div></div>").css({
                position: "absolute",
                visibility: "visible",
                left: -s * u,
                top: -i * d
            }).parent().addClass("ui-effects-explode").css({
                position: "absolute",
                overflow: "hidden",
                width: u,
                height: d,
                left: o + (p ? r * u : 0),
                top: n + (p ? a * d : 0),
                opacity: p ? 0 : 1
            }).animate({
                left: o + (p ? 0 : r * u),
                top: n + (p ? 0 : a * d),
                opacity: p ? 1 : 0
            }, t.duration || 500, t.easing, m)
    }), x.effects.define("fade", "toggle", function(t, e) {
        var i = "show" === t.mode;
        x(this).css("opacity", i ? 0 : 1).animate({
            opacity: i ? 1 : 0
        }, {
            queue: !1,
            duration: t.duration,
            easing: t.easing,
            complete: e
        })
    }), x.effects.define("fold", "hide", function(e, t) {
        var i = x(this),
            s = e.mode,
            o = "show" === s,
            n = "hide" === s,
            r = e.size || 15,
            a = /([0-9]+)%/.exec(r),
            h = !!e.horizFirst ? ["right", "bottom"] : ["bottom", "right"],
            l = e.duration / 2,
            c = x.effects.createPlaceholder(i),
            p = i.cssClip(),
            f = {
                clip: x.extend({}, p)
            },
            u = {
                clip: x.extend({}, p)
            },
            d = [p[h[0]], p[h[1]]],
            s = i.queue().length;
        a && (r = parseInt(a[1], 10) / 100 * d[n ? 0 : 1]), f.clip[h[0]] = r, u.clip[h[0]] = r, u.clip[h[1]] = 0, o && (i.cssClip(u.clip), c && c.css(x.effects.clipToBox(u)), u.clip = p), i.queue(function(t) {
            c && c.animate(x.effects.clipToBox(f), l, e.easing).animate(x.effects.clipToBox(u), l, e.easing), t()
        }).animate(f, l, e.easing).animate(u, l, e.easing).queue(t), x.effects.unshift(i, s, 4)
    }), x.effects.define("highlight", "show", function(t, e) {
        var i = x(this),
            s = {
                backgroundColor: i.css("backgroundColor")
            };
        "hide" === t.mode && (s.opacity = 0), x.effects.saveStyle(i), i.css({
            backgroundImage: "none",
            backgroundColor: t.color || "#ffff99"
        }).animate(s, {
            queue: !1,
            duration: t.duration,
            easing: t.easing,
            complete: e
        })
    }), x.effects.define("size", function(s, e) {
        var o, i = x(this),
            t = ["fontSize"],
            n = ["borderTopWidth", "borderBottomWidth", "paddingTop", "paddingBottom"],
            r = ["borderLeftWidth", "borderRightWidth", "paddingLeft", "paddingRight"],
            a = s.mode,
            h = "effect" !== a,
            l = s.scale || "both",
            c = s.origin || ["middle", "center"],
            p = i.css("position"),
            f = i.position(),
            u = x.effects.scaledDimensions(i),
            d = s.from || u,
            g = s.to || x.effects.scaledDimensions(i, 0);
        x.effects.createPlaceholder(i), "show" === a && (a = d, d = g, g = a), o = {
            from: {
                y: d.height / u.height,
                x: d.width / u.width
            },
            to: {
                y: g.height / u.height,
                x: g.width / u.width
            }
        }, "box" !== l && "both" !== l || (o.from.y !== o.to.y && (d = x.effects.setTransition(i, n, o.from.y, d), g = x.effects.setTransition(i, n, o.to.y, g)), o.from.x !== o.to.x && (d = x.effects.setTransition(i, r, o.from.x, d), g = x.effects.setTransition(i, r, o.to.x, g))), "content" !== l && "both" !== l || o.from.y !== o.to.y && (d = x.effects.setTransition(i, t, o.from.y, d), g = x.effects.setTransition(i, t, o.to.y, g)), c && (c = x.effects.getBaseline(c, u), d.top = (u.outerHeight - d.outerHeight) * c.y + f.top, d.left = (u.outerWidth - d.outerWidth) * c.x + f.left, g.top = (u.outerHeight - g.outerHeight) * c.y + f.top, g.left = (u.outerWidth - g.outerWidth) * c.x + f.left), delete d.outerHeight, delete d.outerWidth, i.css(d), "content" !== l && "both" !== l || (n = n.concat(["marginTop", "marginBottom"]).concat(t), r = r.concat(["marginLeft", "marginRight"]), i.find("*[width]").each(function() {
            var t = x(this),
                e = x.effects.scaledDimensions(t),
                i = {
                    height: e.height * o.from.y,
                    width: e.width * o.from.x,
                    outerHeight: e.outerHeight * o.from.y,
                    outerWidth: e.outerWidth * o.from.x
                },
                e = {
                    height: e.height * o.to.y,
                    width: e.width * o.to.x,
                    outerHeight: e.height * o.to.y,
                    outerWidth: e.width * o.to.x
                };
            o.from.y !== o.to.y && (i = x.effects.setTransition(t, n, o.from.y, i), e = x.effects.setTransition(t, n, o.to.y, e)), o.from.x !== o.to.x && (i = x.effects.setTransition(t, r, o.from.x, i), e = x.effects.setTransition(t, r, o.to.x, e)), h && x.effects.saveStyle(t), t.css(i), t.animate(e, s.duration, s.easing, function() {
                h && x.effects.restoreStyle(t)
            })
        })), i.animate(g, {
            queue: !1,
            duration: s.duration,
            easing: s.easing,
            complete: function() {
                var t = i.offset();
                0 === g.opacity && i.css("opacity", d.opacity), h || (i.css("position", "static" === p ? "relative" : p).offset(t), x.effects.saveStyle(i)), e()
            }
        })
    }), x.effects.define("scale", function(t, e) {
        var i = x(this),
            s = t.mode,
            s = parseInt(t.percent, 10) || (0 === parseInt(t.percent, 10) || "effect" !== s ? 0 : 100),
            s = x.extend(!0, {
                from: x.effects.scaledDimensions(i),
                to: x.effects.scaledDimensions(i, s, t.direction || "both"),
                origin: t.origin || ["middle", "center"]
            }, t);
        t.fade && (s.from.opacity = 1, s.to.opacity = 0), x.effects.effect.size.call(this, s, e)
    }), x.effects.define("puff", "hide", function(t, e) {
        t = x.extend(!0, {}, t, {
            fade: !0,
            percent: parseInt(t.percent, 10) || 150
        });
        x.effects.effect.scale.call(this, t, e)
    }), x.effects.define("pulsate", "show", function(t, e) {
        var i = x(this),
            s = t.mode,
            o = "show" === s,
            n = 2 * (t.times || 5) + (o || "hide" === s ? 1 : 0),
            r = t.duration / n,
            a = 0,
            h = 1,
            s = i.queue().length;
        for (!o && i.is(":visible") || (i.css("opacity", 0).show(), a = 1); h < n; h++) i.animate({
            opacity: a
        }, r, t.easing), a = 1 - a;
        i.animate({
            opacity: a
        }, r, t.easing), i.queue(e), x.effects.unshift(i, s, 1 + n)
    }), x.effects.define("shake", function(t, e) {
        var i = 1,
            s = x(this),
            o = t.direction || "left",
            n = t.distance || 20,
            r = t.times || 3,
            a = 2 * r + 1,
            h = Math.round(t.duration / a),
            l = "up" === o || "down" === o ? "top" : "left",
            c = "up" === o || "left" === o,
            p = {},
            f = {},
            u = {},
            o = s.queue().length;
        for (x.effects.createPlaceholder(s), p[l] = (c ? "-=" : "+=") + n, f[l] = (c ? "+=" : "-=") + 2 * n, u[l] = (c ? "-=" : "+=") + 2 * n, s.animate(p, h, t.easing); i < r; i++) s.animate(f, h, t.easing).animate(u, h, t.easing);
        s.animate(f, h, t.easing).animate(p, h / 2, t.easing).queue(e), x.effects.unshift(s, o, 1 + a)
    }), x.effects.define("slide", "show", function(t, e) {
        var i, s, o = x(this),
            n = {
                up: ["bottom", "top"],
                down: ["top", "bottom"],
                left: ["right", "left"],
                right: ["left", "right"]
            },
            r = t.mode,
            a = t.direction || "left",
            h = "up" === a || "down" === a ? "top" : "left",
            l = "up" === a || "left" === a,
            c = t.distance || o["top" == h ? "outerHeight" : "outerWidth"](!0),
            p = {};
        x.effects.createPlaceholder(o), i = o.cssClip(), s = o.position()[h], p[h] = (l ? -1 : 1) * c + s, p.clip = o.cssClip(), p.clip[n[a][1]] = p.clip[n[a][0]], "show" === r && (o.cssClip(p.clip), o.css(h, p[h]), p.clip = i, p[h] = s), o.animate(p, {
            queue: !1,
            duration: t.duration,
            easing: t.easing,
            complete: e
        })
    }), !1 !== x.uiBackCompat && x.effects.define("transfer", function(t, e) {
        x(this).transfer(t, e)
    })
});