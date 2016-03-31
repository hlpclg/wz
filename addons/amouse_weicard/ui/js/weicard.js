/*** Unobtrusive Ajax support library for jQuery*/
(function (a) {
    var b = "unobtrusiveAjaxClick", g = "unobtrusiveValidation";

    function c(d, b) {
        var a = window, c = (d || "").split(".");
        while (a && c.length) a = a[c.shift()];
        if (typeof a === "function") return a;
        b.push(d);
        return Function.constructor.apply(null, b)
    }

    function d(a) {
        return a === "GET" || a === "POST"
    }

    function f(b, a) {
        !d(a) && b.setRequestHeader("X-HTTP-Method-Override", a)
    }

    function h(c, b, e) {
        var d;
        if (e.indexOf("application/x-javascript") !== -1) return;
        d = (c.getAttribute("data-ajax-mode") || "").toUpperCase();
        a(c.getAttribute("data-ajax-update")).each(function (f, c) {
            var e;
            switch (d) {
                case "BEFORE":
                    e = c.firstChild;
                    a("<div />").html(b).contents().each(function () {
                        c.insertBefore(this, e)
                    });
                    break;
                case "AFTER":
                    a("<div />").html(b).contents().each(function () {
                        c.appendChild(this)
                    });
                    break;
                default:
                    a(c).html(b)
            }
        })
    }

    function e(b, e) {
        var j, k, g, i;
        j = b.getAttribute("data-ajax-confirm");
        if (j && !window.confirm(j)) return;
        k = a(b.getAttribute("data-ajax-loading"));
        i = b.getAttribute("data-ajax-loading-duration") || 0;
        a.extend(e, { type: b.getAttribute("data-ajax-method") || undefined, url: b.getAttribute("data-ajax-url") || undefined, beforeSend: function (d) {
            var a;
            f(d, g);
            a = c(b.getAttribute("data-ajax-begin"), ["xhr"]).apply(this, arguments);
            a !== false && k.show(i);
            return a
        }, complete: function () {
            k.hide(i);
            c(b.getAttribute("data-ajax-complete"), ["xhr", "status"]).apply(this, arguments)
        }, success: function (a, e, d) {
            h(b, a, d.getResponseHeader("Content-Type") || "text/html");
            c(b.getAttribute("data-ajax-success"), ["data", "status", "xhr"]).apply(this, arguments)
        }, error: c(b.getAttribute("data-ajax-failure"), ["xhr", "status", "error"]) });
        e.data.push({ name: "X-Requested-With", value: "XMLHttpRequest" });
        g = e.type.toUpperCase();
        if (!d(g)) {
            e.type = "POST";
            e.data.push({ name: "X-HTTP-Method-Override", value: g })
        }
        a.ajax(e)
    }

    function i(c) {
        var b = a(c).data(g);
        return !b || !b.validate || b.validate()
    }

    a("a[data-ajax=true]").live("click", function (a) {
        a.preventDefault();
        e(this, { url: this.href, type: "GET", data: [] })
    });
    a("form[data-ajax=true] input[type=image]").live("click", function (c) {
        var g = c.target.name, d = a(c.target), f = d.parents("form")[0], e = d.offset();
        a(f).data(b, [
            { name: g + ".x", value: Math.round(c.pageX - e.left) },
            { name: g + ".y", value: Math.round(c.pageY - e.top) }
        ]);
        setTimeout(function () {
            a(f).removeData(b)
        }, 0)
    });
    a("form[data-ajax=true] :submit").live("click", function (c) {
        var e = c.target.name, d = a(c.target).parents("form")[0];
        a(d).data(b, e ? [
            { name: e, value: c.target.value }
        ] : []);
        setTimeout(function () {
            a(d).removeData(b)
        }, 0)
    });
    a("form[data-ajax=true]").live("submit", function (d) {
        var c = a(this).data(b) || [];
        d.preventDefault();
        if (!i(this)) return;
        e(this, { url: this.action, type: this.method || "GET", data: c.concat(a(this).serializeArray()) })
    })
})(jQuery);
/** * jQuery Validation Plugin 1.9.0*/
(function (c) {
    c.extend(c.fn, {
        validate: function (a) {
            if (this.length) {
                var b = c.data(this[0], "validator");
                if (b) return b;
                this.attr("novalidate", "novalidate");
                b = new c.validator(a, this[0]);
                c.data(this[0], "validator", b);
                if (b.settings.onsubmit) {
                    a = this.find("input, button");
                    a.filter(".cancel").click(function () {
                        b.cancelSubmit = true
                    });
                    b.settings.submitHandler && a.filter(":submit").click(function () {
                        b.submitButton = this
                    });
                    this.submit(function (d) {
                        function e() {
                            if (b.settings.submitHandler) {
                                if (b.submitButton) var f = c("<input type='hidden'/>").attr("name",
                                    b.submitButton.name).val(b.submitButton.value).appendTo(b.currentForm);
                                b.settings.submitHandler.call(b, b.currentForm);
                                b.submitButton && f.remove();
                                return false
                            }
                            return true
                        }

                        b.settings.debug && d.preventDefault();
                        if (b.cancelSubmit) {
                            b.cancelSubmit = false;
                            return e()
                        }
                        if (b.form()) {
                            if (b.pendingRequest) {
                                b.formSubmitted = true;
                                return false
                            }
                            return e()
                        } else {
                            b.focusInvalid();
                            return false
                        }
                    })
                }
                return b
            } else a && a.debug && window.console && console.warn("nothing selected, can't validate, returning nothing")
        }, valid: function () {
            if (c(this[0]).is("form")) return this.validate().form();
            else {
                var a = true, b = c(this[0].form).validate();
                this.each(function () {
                    a &= b.element(this)
                });
                return a
            }
        }, removeAttrs: function (a) {
            var b = {}, d = this;
            c.each(a.split(/\s/), function (e, f) {
                b[f] = d.attr(f);
                d.removeAttr(f)
            });
            return b
        }, rules: function (a, b) {
            var d = this[0];
            if (a) {
                var e = c.data(d.form, "validator").settings, f = e.rules, g = c.validator.staticRules(d);
                switch (a) {
                    case "add":
                        c.extend(g, c.validator.normalizeRule(b));
                        f[d.name] = g;
                        if (b.messages) e.messages[d.name] = c.extend(e.messages[d.name], b.messages);
                        break;
                    case "remove":
                        if (!b) {
                            delete f[d.name];
                            return g
                        }
                        var h = {};
                        c.each(b.split(/\s/), function (j, i) {
                            h[i] = g[i];
                            delete g[i]
                        });
                        return h
                }
            }
            d = c.validator.normalizeRules(c.extend({}, c.validator.metadataRules(d), c.validator.classRules(d), c.validator.attributeRules(d), c.validator.staticRules(d)), d);
            if (d.required) {
                e = d.required;
                delete d.required;
                d = c.extend({ required: e }, d)
            }
            return d
        }
    });
    c.extend(c.expr[":"], { blank: function (a) {
        return !c.trim("" + a.value)
    }, filled: function (a) {
        return !!c.trim("" + a.value)
    }, unchecked: function (a) {
        return !a.checked
    } });
    c.validator = function (a, b) {
        this.settings = c.extend(true, {}, c.validator.defaults, a);
        this.currentForm = b;
        this.init()
    };
    c.validator.format = function (a, b) {
        if (arguments.length == 1) return function () {
            var d = c.makeArray(arguments);
            d.unshift(a);
            return c.validator.format.apply(this, d)
        };
        if (arguments.length > 2 && b.constructor != Array) b = c.makeArray(arguments).slice(1);
        if (b.constructor != Array) b = [b];
        c.each(b, function (d, e) {
            a = a.replace(RegExp("\\{" + d + "\\}", "g"), e)
        });
        return a
    };
    c.extend(c.validator, {
        defaults: {
            messages: {}, groups: {}, rules: {}, errorClass: "error",
            validClass: "valid", errorElement: "label", focusInvalid: true, errorContainer: c([]), errorLabelContainer: c([]), onsubmit: true, ignore: ":hidden", ignoreTitle: false, onfocusin: function (a) {
                this.lastActive = a;
                if (this.settings.focusCleanup && !this.blockFocusCleanup) {
                    this.settings.unhighlight && this.settings.unhighlight.call(this, a, this.settings.errorClass, this.settings.validClass);
                    this.addWrapper(this.errorsFor(a)).hide()
                }
            }, onfocusout: function (a) {
                if (!this.checkable(a) && (a.name in this.submitted || !this.optional(a))) this.element(a)
            },
            onkeyup: function (a) {
                if (a.name in this.submitted || a == this.lastElement) this.element(a)
            }, onclick: function (a) {
                if (a.name in this.submitted) this.element(a); else a.parentNode.name in this.submitted && this.element(a.parentNode)
            }, highlight: function (a, b, d) {
                a.type === "radio" ? this.findByName(a.name).addClass(b).removeClass(d) : c(a).addClass(b).removeClass(d)
            }, unhighlight: function (a, b, d) {
                a.type === "radio" ? this.findByName(a.name).removeClass(b).addClass(d) : c(a).removeClass(b).addClass(d)
            }
        }, setDefaults: function (a) {
            c.extend(c.validator.defaults,
                a)
        }, messages: {
            required: "This field is required.", remote: "Please fix this field.", email: "Please enter a valid email address.", url: "Please enter a valid URL.", date: "Please enter a valid date.", dateISO: "Please enter a valid date (ISO).", number: "Please enter a valid number.", digits: "Please enter only digits.", creditcard: "Please enter a valid credit card number.", equalTo: "Please enter the same value again.", accept: "Please enter a value with a valid extension.", maxlength: c.validator.format("Please enter no more than {0} characters."),
            minlength: c.validator.format("Please enter at least {0} characters."), rangelength: c.validator.format("Please enter a value between {0} and {1} characters long."), range: c.validator.format("Please enter a value between {0} and {1}."), max: c.validator.format("Please enter a value less than or equal to {0}."), min: c.validator.format("Please enter a value greater than or equal to {0}.")
        }, autoCreateRanges: false, prototype: {
            init: function () {
                function a(e) {
                    var f = c.data(this[0].form, "validator"), g = "on" + e.type.replace(/^validate/,
                        "");
                    f.settings[g] && f.settings[g].call(f, this[0], e)
                }

                this.labelContainer = c(this.settings.errorLabelContainer);
                this.errorContext = this.labelContainer.length && this.labelContainer || c(this.currentForm);
                this.containers = c(this.settings.errorContainer).add(this.settings.errorLabelContainer);
                this.submitted = {};
                this.valueCache = {};
                this.pendingRequest = 0;
                this.pending = {};
                this.invalid = {};
                this.reset();
                var b = this.groups = {};
                c.each(this.settings.groups, function (e, f) {
                    c.each(f.split(/\s/), function (g, h) {
                        b[h] = e
                    })
                });
                var d =
                    this.settings.rules;
                c.each(d, function (e, f) {
                    d[e] = c.validator.normalizeRule(f)
                });
                c(this.currentForm).validateDelegate("[type='text'], [type='password'], [type='file'], select, textarea, [type='number'], [type='search'] ,[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'] ", "focusin focusout keyup", a).validateDelegate("[type='radio'], [type='checkbox'], select, option", "click",
                    a);
                this.settings.invalidHandler && c(this.currentForm).bind("invalid-form.validate", this.settings.invalidHandler)
            }, form: function () {
                this.checkForm();
                c.extend(this.submitted, this.errorMap);
                this.invalid = c.extend({}, this.errorMap);
                this.valid() || c(this.currentForm).triggerHandler("invalid-form", [this]);
                this.showErrors();
                return this.valid()
            }, checkForm: function () {
                this.prepareForm();
                for (var a = 0, b = this.currentElements = this.elements(); b[a]; a++) this.check(b[a]);
                return this.valid()
            }, element: function (a) {
                this.lastElement =
                    a = this.validationTargetFor(this.clean(a));
                this.prepareElement(a);
                this.currentElements = c(a);
                var b = this.check(a);
                if (b) delete this.invalid[a.name]; else this.invalid[a.name] = true;
                if (!this.numberOfInvalids()) this.toHide = this.toHide.add(this.containers);
                this.showErrors();
                return b
            }, showErrors: function (a) {
                if (a) {
                    c.extend(this.errorMap, a);
                    this.errorList = [];
                    for (var b in a) this.errorList.push({ message: a[b], element: this.findByName(b)[0] });
                    this.successList = c.grep(this.successList, function (d) {
                        return !(d.name in a)
                    })
                }
                this.settings.showErrors ?
                    this.settings.showErrors.call(this, this.errorMap, this.errorList) : this.defaultShowErrors()
            }, resetForm: function () {
                c.fn.resetForm && c(this.currentForm).resetForm();
                this.submitted = {};
                this.lastElement = null;
                this.prepareForm();
                this.hideErrors();
                this.elements().removeClass(this.settings.errorClass)
            }, numberOfInvalids: function () {
                return this.objectLength(this.invalid)
            }, objectLength: function (a) {
                var b = 0, d;
                for (d in a) b++;
                return b
            }, hideErrors: function () {
                this.addWrapper(this.toHide).hide()
            }, valid: function () {
                return this.size() ==
                    0
            }, size: function () {
                return this.errorList.length
            }, focusInvalid: function () {
                if (this.settings.focusInvalid) try {
                    c(this.findLastActive() || this.errorList.length && this.errorList[0].element || []).filter(":visible").focus().trigger("focusin")
                } catch (a) {
                }
            }, findLastActive: function () {
                var a = this.lastActive;
                return a && c.grep(this.errorList,function (b) {
                    return b.element.name == a.name
                }).length == 1 && a
            }, elements: function () {
                var a = this, b = {};
                return c(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function () {
                    !this.name &&
                        a.settings.debug && window.console && console.error("%o has no name assigned", this);
                    if (this.name in b || !a.objectLength(c(this).rules())) return false;
                    return b[this.name] = true
                })
            }, clean: function (a) {
                return c(a)[0]
            }, errors: function () {
                return c(this.settings.errorElement + "." + this.settings.errorClass, this.errorContext)
            }, reset: function () {
                this.successList = [];
                this.errorList = [];
                this.errorMap = {};
                this.toShow = c([]);
                this.toHide = c([]);
                this.currentElements = c([])
            }, prepareForm: function () {
                this.reset();
                this.toHide = this.errors().add(this.containers)
            },
            prepareElement: function (a) {
                this.reset();
                this.toHide = this.errorsFor(a)
            }, check: function (a) {
                a = this.validationTargetFor(this.clean(a));
                var b = c(a).rules(), d = false, e;
                for (e in b) {
                    var f = { method: e, parameters: b[e] };
                    try {
                        var g = c.validator.methods[e].call(this, a.value.replace(/\r/g, ""), a, f.parameters);
                        if (g == "dependency-mismatch") d = true; else {
                            d = false;
                            if (g == "pending") {
                                this.toHide = this.toHide.not(this.errorsFor(a));
                                return
                            }
                            if (!g) {
                                this.formatAndAdd(a, f);
                                return false
                            }
                        }
                    } catch (h) {
                        this.settings.debug && window.console && console.log("exception occured when checking element " +
                            a.id + ", check the '" + f.method + "' method", h);
                        throw h;
                    }
                }
                if (!d) {
                    this.objectLength(b) && this.successList.push(a);
                    return true
                }
            }, customMetaMessage: function (a, b) {
                if (c.metadata) {
                    var d = this.settings.meta ? c(a).metadata()[this.settings.meta] : c(a).metadata();
                    return d && d.messages && d.messages[b]
                }
            }, customMessage: function (a, b) {
                var d = this.settings.messages[a];
                return d && (d.constructor == String ? d : d[b])
            }, findDefined: function () {
                for (var a = 0; a < arguments.length; a++) if (arguments[a] !== undefined) return arguments[a]
            }, defaultMessage: function (a, b) {
                return this.findDefined(this.customMessage(a.name, b), this.customMetaMessage(a, b), !this.settings.ignoreTitle && a.title || undefined, c.validator.messages[b], "<strong>Warning: No message defined for " + a.name + "</strong>")
            }, formatAndAdd: function (a, b) {
                var d = this.defaultMessage(a, b.method), e = /\$?\{(\d+)\}/g;
                if (typeof d == "function") d = d.call(this, b.parameters, a); else if (e.test(d)) d = jQuery.format(d.replace(e, "{$1}"), b.parameters);
                this.errorList.push({ message: d, element: a });
                this.errorMap[a.name] = d;
                this.submitted[a.name] =
                    d
            }, addWrapper: function (a) {
                if (this.settings.wrapper) a = a.add(a.parent(this.settings.wrapper));
                return a
            }, defaultShowErrors: function () {
                for (var a = 0; this.errorList[a]; a++) {
                    var b = this.errorList[a];
                    this.settings.highlight && this.settings.highlight.call(this, b.element, this.settings.errorClass, this.settings.validClass);
                    this.showLabel(b.element, b.message)
                }
                if (this.errorList.length) this.toShow = this.toShow.add(this.containers);
                if (this.settings.success) for (a = 0; this.successList[a]; a++) this.showLabel(this.successList[a]);
                if (this.settings.unhighlight) {
                    a = 0;
                    for (b = this.validElements(); b[a]; a++) this.settings.unhighlight.call(this, b[a], this.settings.errorClass, this.settings.validClass)
                }
                this.toHide = this.toHide.not(this.toShow);
                this.hideErrors();
                this.addWrapper(this.toShow).show()
            }, validElements: function () {
                return this.currentElements.not(this.invalidElements())
            }, invalidElements: function () {
                return c(this.errorList).map(function () {
                    return this.element
                })
            }, showLabel: function (a, b) {
                var d = this.errorsFor(a);
                if (d.length) {
                    d.removeClass(this.settings.validClass).addClass(this.settings.errorClass);
                    d.attr("generated") && d.html(b)
                } else {
                    d = c("<" + this.settings.errorElement + "/>").attr({ "for": this.idOrName(a), generated: true }).addClass(this.settings.errorClass).html(b || "");
                    if (this.settings.wrapper) d = d.hide().show().wrap("<" + this.settings.wrapper + "/>").parent();
                    this.labelContainer.append(d).length || (this.settings.errorPlacement ? this.settings.errorPlacement(d, c(a)) : d.insertAfter(a))
                }
                if (!b && this.settings.success) {
                    d.text("");
                    typeof this.settings.success == "string" ? d.addClass(this.settings.success) : this.settings.success(d)
                }
                this.toShow =
                    this.toShow.add(d)
            }, errorsFor: function (a) {
                var b = this.idOrName(a);
                return this.errors().filter(function () {
                    return c(this).attr("for") == b
                })
            }, idOrName: function (a) {
                return this.groups[a.name] || (this.checkable(a) ? a.name : a.id || a.name)
            }, validationTargetFor: function (a) {
                if (this.checkable(a)) a = this.findByName(a.name).not(this.settings.ignore)[0];
                return a
            }, checkable: function (a) {
                return /radio|checkbox/i.test(a.type)
            }, findByName: function (a) {
                var b = this.currentForm;
                return c(document.getElementsByName(a)).map(function (d, e) {
                    return e.form == b && e.name == a && e || null
                })
            }, getLength: function (a, b) {
                switch (b.nodeName.toLowerCase()) {
                    case "select":
                        return c("option:selected", b).length;
                    case "input":
                        if (this.checkable(b)) return this.findByName(b.name).filter(":checked").length
                }
                return a.length
            }, depend: function (a, b) {
                return this.dependTypes[typeof a] ? this.dependTypes[typeof a](a, b) : true
            }, dependTypes: { "boolean": function (a) {
                return a
            }, string: function (a, b) {
                return !!c(a, b.form).length
            }, "function": function (a, b) {
                return a(b)
            } }, optional: function (a) {
                return !c.validator.methods.required.call(this,
                    c.trim(a.value), a) && "dependency-mismatch"
            }, startRequest: function (a) {
                if (!this.pending[a.name]) {
                    this.pendingRequest++;
                    this.pending[a.name] = true
                }
            }, stopRequest: function (a, b) {
                this.pendingRequest--;
                if (this.pendingRequest < 0) this.pendingRequest = 0;
                delete this.pending[a.name];
                if (b && this.pendingRequest == 0 && this.formSubmitted && this.form()) {
                    c(this.currentForm).submit();
                    this.formSubmitted = false
                } else if (!b && this.pendingRequest == 0 && this.formSubmitted) {
                    c(this.currentForm).triggerHandler("invalid-form", [this]);
                    this.formSubmitted =
                        false
                }
            }, previousValue: function (a) {
                return c.data(a, "previousValue") || c.data(a, "previousValue", { old: null, valid: true, message: this.defaultMessage(a, "remote") })
            }
        }, classRuleSettings: { required: { required: true }, email: { email: true }, url: { url: true }, date: { date: true }, dateISO: { dateISO: true }, dateDE: { dateDE: true }, number: { number: true }, numberDE: { numberDE: true }, digits: { digits: true }, creditcard: { creditcard: true } }, addClassRules: function (a, b) {
            a.constructor == String ? this.classRuleSettings[a] = b : c.extend(this.classRuleSettings,
                a)
        }, classRules: function (a) {
            var b = {};
            (a = c(a).attr("class")) && c.each(a.split(" "), function () {
                this in c.validator.classRuleSettings && c.extend(b, c.validator.classRuleSettings[this])
            });
            return b
        }, attributeRules: function (a) {
            var b = {};
            a = c(a);
            for (var d in c.validator.methods) {
                var e;
                if (e = d === "required" && typeof c.fn.prop === "function" ? a.prop(d) : a.attr(d)) b[d] = e; else if (a[0].getAttribute("type") === d) b[d] = true
            }
            b.maxlength && /-1|2147483647|524288/.test(b.maxlength) && delete b.maxlength;
            return b
        }, metadataRules: function (a) {
            if (!c.metadata) return {};
            var b = c.data(a.form, "validator").settings.meta;
            return b ? c(a).metadata()[b] : c(a).metadata()
        }, staticRules: function (a) {
            var b = {}, d = c.data(a.form, "validator");
            if (d.settings.rules) b = c.validator.normalizeRule(d.settings.rules[a.name]) || {};
            return b
        }, normalizeRules: function (a, b) {
            c.each(a, function (d, e) {
                if (e === false) delete a[d]; else if (e.param || e.depends) {
                    var f = true;
                    switch (typeof e.depends) {
                        case "string":
                            f = !!c(e.depends, b.form).length;
                            break;
                        case "function":
                            f = e.depends.call(b, b)
                    }
                    if (f) a[d] = e.param !== undefined ?
                        e.param : true; else delete a[d]
                }
            });
            c.each(a, function (d, e) {
                a[d] = c.isFunction(e) ? e(b) : e
            });
            c.each(["minlength", "maxlength", "min", "max"], function () {
                if (a[this]) a[this] = Number(a[this])
            });
            c.each(["rangelength", "range"], function () {
                if (a[this]) a[this] = [Number(a[this][0]), Number(a[this][1])]
            });
            if (c.validator.autoCreateRanges) {
                if (a.min && a.max) {
                    a.range = [a.min, a.max];
                    delete a.min;
                    delete a.max
                }
                if (a.minlength && a.maxlength) {
                    a.rangelength = [a.minlength, a.maxlength];
                    delete a.minlength;
                    delete a.maxlength
                }
            }
            a.messages && delete a.messages;
            return a
        }, normalizeRule: function (a) {
            if (typeof a == "string") {
                var b = {};
                c.each(a.split(/\s/), function () {
                    b[this] = true
                });
                a = b
            }
            return a
        }, addMethod: function (a, b, d) {
            c.validator.methods[a] = b;
            c.validator.messages[a] = d != undefined ? d : c.validator.messages[a];
            b.length < 3 && c.validator.addClassRules(a, c.validator.normalizeRule(a))
        }, methods: {
            required: function (a, b, d) {
                if (!this.depend(d, b)) return "dependency-mismatch";
                switch (b.nodeName.toLowerCase()) {
                    case "select":
                        return (a = c(b).val()) && a.length > 0;
                    case "input":
                        if (this.checkable(b)) return this.getLength(a,
                            b) > 0;
                    default:
                        return c.trim(a).length > 0
                }
            }, remote: function (a, b, d) {
                if (this.optional(b)) return "dependency-mismatch";
                var e = this.previousValue(b);
                this.settings.messages[b.name] || (this.settings.messages[b.name] = {});
                e.originalMessage = this.settings.messages[b.name].remote;
                this.settings.messages[b.name].remote = e.message;
                d = typeof d == "string" && { url: d } || d;
                if (this.pending[b.name]) return "pending";
                if (e.old === a) return e.valid;
                e.old = a;
                var f = this;
                this.startRequest(b);
                var g = {};
                g[b.name] = a;
                c.ajax(c.extend(true, {
                    url: d,
                    mode: "abort", port: "validate" + b.name, dataType: "json", data: g, success: function (h) {
                        f.settings.messages[b.name].remote = e.originalMessage;
                        var j = h === true;
                        if (j) {
                            var i = f.formSubmitted;
                            f.prepareElement(b);
                            f.formSubmitted = i;
                            f.successList.push(b);
                            f.showErrors()
                        } else {
                            i = {};
                            h = h || f.defaultMessage(b, "remote");
                            i[b.name] = e.message = c.isFunction(h) ? h(a) : h;
                            f.showErrors(i)
                        }
                        e.valid = j;
                        f.stopRequest(b, j)
                    }
                }, d));
                return "pending"
            }, minlength: function (a, b, d) {
                return this.optional(b) || this.getLength(c.trim(a), b) >= d
            }, maxlength: function (a, b, d) {
                return this.optional(b) || this.getLength(c.trim(a), b) <= d
            }, rangelength: function (a, b, d) {
                a = this.getLength(c.trim(a), b);
                return this.optional(b) || a >= d[0] && a <= d[1]
            }, min: function (a, b, d) {
                return this.optional(b) || a >= d
            }, max: function (a, b, d) {
                return this.optional(b) || a <= d
            }, range: function (a, b, d) {
                return this.optional(b) || a >= d[0] && a <= d[1]
            }, email: function (a, b) {
                return this.optional(b) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(a)
            },
            url: function (a, b) {
                return this.optional(b) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(a)
            },
            date: function (a, b) {
                return this.optional(b) || !/Invalid|NaN/.test(new Date(a))
            }, dateISO: function (a, b) {
                return this.optional(b) || /^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(a)
            }, number: function (a, b) {
                return this.optional(b) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(a)
            }, digits: function (a, b) {
                return this.optional(b) || /^\d+$/.test(a)
            }, creditcard: function (a, b) {
                if (this.optional(b)) return "dependency-mismatch";
                if (/[^0-9 -]+/.test(a)) return false;
                var d = 0, e = 0, f = false;
                a = a.replace(/\D/g, "");
                for (var g = a.length - 1; g >=
                    0; g--) {
                    e = a.charAt(g);
                    e = parseInt(e, 10);
                    if (f) if ((e *= 2) > 9) e -= 9;
                    d += e;
                    f = !f
                }
                return d % 10 == 0
            }, accept: function (a, b, d) {
                d = typeof d == "string" ? d.replace(/,/g, "|") : "png|jpe?g|gif";
                return this.optional(b) || a.match(RegExp(".(" + d + ")$", "i"))
            }, equalTo: function (a, b, d) {
                d = c(d).unbind(".validate-equalTo").bind("blur.validate-equalTo", function () {
                    c(b).valid()
                });
                return a == d.val()
            }
        }
    });
    c.format = c.validator.format
})(jQuery);
(function (c) {
    var a = {};
    if (c.ajaxPrefilter) c.ajaxPrefilter(function (d, e, f) {
        e = d.port;
        if (d.mode == "abort") {
            a[e] && a[e].abort();
            a[e] = f
        }
    }); else {
        var b = c.ajax;
        c.ajax = function (d) {
            var e = ("port" in d ? d : c.ajaxSettings).port;
            if (("mode" in d ? d : c.ajaxSettings).mode == "abort") {
                a[e] && a[e].abort();
                return a[e] = b.apply(this, arguments)
            }
            return b.apply(this, arguments)
        }
    }
})(jQuery);
(function (c) {
    !jQuery.event.special.focusin && !jQuery.event.special.focusout && document.addEventListener && c.each({ focus: "focusin", blur: "focusout" }, function (a, b) {
        function d(e) {
            e = c.event.fix(e);
            e.type = b;
            return c.event.handle.call(this, e)
        }

        c.event.special[b] = { setup: function () {
            this.addEventListener(a, d, true)
        }, teardown: function () {
            this.removeEventListener(a, d, true)
        }, handler: function (e) {
            arguments[0] = c.event.fix(e);
            arguments[0].type = b;
            return c.event.handle.apply(this, arguments)
        } }
    });
    c.extend(c.fn, {
        validateDelegate: function (a, b, d) {
            return this.bind(b, function (e) {
                var f = c(e.target);
                if (f.is(a)) return d.apply(f, arguments)
            })
        }
    })
})(jQuery);
/*** Unobtrusive validation support library for jQuery and jQuery Validate*/
(function (a) {
    var d = a.validator, b, f = "unobtrusiveValidation";

    function c(a, b, c) {
        a.rules[b] = c;
        if (a.message) a.messages[b] = a.message
    }

    function i(a) {
        return a.replace(/^\s+|\s+$/g, "").split(/\s*,\s*/g)
    }

    function g(a) {
        return a.substr(0, a.lastIndexOf(".") + 1)
    }

    function e(a, b) {
        if (a.indexOf("*.") === 0) a = a.replace("*.", b);
        return a
    }

    function l(c, d) {
        var b = a(this).find("[data-valmsg-for='" + d[0].name + "']"), e = a.parseJSON(b.attr("data-valmsg-replace")) !== false;
        b.removeClass("field-validation-valid").addClass("field-validation-error");
        c.data("unobtrusiveContainer", b);
        if (e) {
            b.empty();
            c.removeClass("input-validation-error").appendTo(b)
        } else c.hide()
    }

    function k(e, d) {
        var c = a(this).find("[data-valmsg-summary=true]"), b = c.find("ul");
        if (b && b.length && d.errorList.length) {
            b.empty();
            c.addClass("validation-summary-errors").removeClass("validation-summary-valid");
            a.each(d.errorList, function () {
                a("<li />").html(this.message).appendTo(b)
            })
        }
    }

    function j(c) {
        var b = c.data("unobtrusiveContainer"), d = a.parseJSON(b.attr("data-valmsg-replace"));
        if (b) {
            b.addClass("field-validation-valid").removeClass("field-validation-error");
            c.removeData("unobtrusiveContainer");
            d && b.empty()
        }
    }

    function h(d) {
        var b = a(d), c = b.data(f);
        if (!c) {
            c = { options: { errorClass: "input-validation-error", errorElement: "span", errorPlacement: a.proxy(l, d), invalidHandler: a.proxy(k, d), messages: {}, rules: {}, success: a.proxy(j, d) }, attachValidation: function () {
                b.validate(this.options)
            }, validate: function () {
                b.validate();
                return b.valid()
            } };
            b.data(f, c)
        }
        return c
    }

    d.unobtrusive = { adapters: [], parseElement: function (b, i) {
        var d = a(b), f = d.parents("form")[0], c, e, g;
        if (!f) return;
        c = h(f);
        c.options.rules[b.name] = e = {};
        c.options.messages[b.name] = g = {};
        a.each(this.adapters, function () {
            var c = "data-val-" + this.name, i = d.attr(c), h = {};
            if (i !== undefined) {
                c += "-";
                a.each(this.params, function () {
                    h[this] = d.attr(c + this)
                });
                this.adapt({ element: b, form: f, message: i, params: h, rules: e, messages: g })
            }
        });
        jQuery.extend(e, { __dummy__: true });
        !i && c.attachValidation()
    }, parse: function (b) {
        a(b).find(":input[data-val=true]").each(function () {
            d.unobtrusive.parseElement(this, true)
        });
        a("form").each(function () {
            var a = h(this);
            a && a.attachValidation()
        })
    } };
    b = d.unobtrusive.adapters;
    b.add = function (c, a, b) {
        if (!b) {
            b = a;
            a = []
        }
        this.push({ name: c, params: a, adapt: b });
        return this
    };
    b.addBool = function (a, b) {
        return this.add(a, function (d) {
            c(d, b || a, true)
        })
    };
    b.addMinMax = function (e, g, f, a, d, b) {
        return this.add(e, [d || "min", b || "max"], function (b) {
            var e = b.params.min, d = b.params.max;
            if (e && d) c(b, a, [e, d]); else if (e) c(b, g, e); else d && c(b, f, d)
        })
    };
    b.addSingleVal = function (a, b, d) {
        return this.add(a, [b || "val"], function (e) {
            c(e, d || a, e.params[b])
        })
    };
    d.addMethod("__dummy__", function () {
        return true
    });
    d.addMethod("regex", function (b, c, d) {
        var a;
        if (this.optional(c)) return true;
        a = (new RegExp(d)).exec(b);
        return a && a.index === 0 && a[0].length === b.length
    });
    b.addSingleVal("accept", "exts").addSingleVal("regex", "pattern");
    b.addBool("creditcard").addBool("date").addBool("digits").addBool("email").addBool("number").addBool("url");
    b.addMinMax("length", "minlength", "maxlength", "rangelength").addMinMax("range", "min", "max", "range");
    b.add("equalto", ["other"], function (b) {
        var h = g(b.element.name), i = b.params.other, d = e(i, h), f = a(b.form).find(":input[name=" + d + "]")[0];
        c(b, "equalTo", f)
    });
    b.add("required", function (a) {
        (a.element.tagName.toUpperCase() !== "INPUT" || a.element.type.toUpperCase() !== "CHECKBOX") && c(a, "required", true)
    });
    b.add("remote", ["url", "type", "additionalfields"], function (b) {
        var d = { url: b.params.url, type: b.params.type || "GET", data: {} }, f = g(b.element.name);
        a.each(i(b.params.additionalfields || b.element.name), function (h, g) {
            var c = e(g, f);
            d.data[c] = function () {
                return a(b.form).find(":input[name='" + c + "']").val()
            }
        });
        c(b, "remote", d)
    });
    a(function () {
        d.unobtrusive.parse(document)
    })
})(jQuery);
//长按事件
(function ($) {
    $.extend($.fn, {
        longPress: function (time, callBack) {
            time = time || 1000;
            var timer = false;
            _this = $(this);
            $(this).live("touchstart",function (e) {
                var i = 0;
                if (timer) {
                    clearTimeout(timer);
                }
                timer = setInterval(function () {
                    i += 10;
                    if (i >= time) {
                        clearTimeout(timer);
                        var positionX = e.pageX - _this.offset().left || 0;
                        var positionY = e.pageY - _this.offset().top || 0;
                        typeof callBack == 'function' && callBack.call(_this, positionX, positionY);
                    }
                }, 10);
                $(document).bind("selectstart", function () {
                    return false;
                });
            }).live("touchend", function () {
                    clearTimeout(timer);
                    $(document).unbind("selectstart");
                })
        }
    });
})(jQuery);

(function ($) {
    $.fn.touchSlide = function () {
        var obj_ = $(this);
        new jQuery.touchSlide(obj_);
        return this;
    };

    $.touchSlide = function (obj_) {
        var wW = $(obj_).width();
        var currentIndex = $(obj_).find(".slide-item").index($(obj_).find(".current"));
        $(obj_).css('left', -currentIndex * wW);
        $(obj_).parent().css({"height": $(obj_).find(".current").height()});

        $(obj_).bind('swipeone', function (event_, obj) {
            event_.preventDefault();
            obj.originalEvent.preventDefault();
            var moveX = obj.delta[0].startX;
            var moveY = obj.delta[0].startY;
            if (Math.abs(moveX) >= 30 && Math.abs(moveX) > Math.abs(moveY)) {
                console.log(moveX);
                if ((moveX > 0 && currentIndex == 0) || (moveX < 0 && currentIndex == $(obj_).find(".slide-item").length - 1)) {
                    return;
                }
                if (moveX > 0 && currentIndex > 0) {
                    currentIndex--;
                } else if (moveX < 0 && currentIndex < $(obj_).find(".slide-item").length - 1) {
                    currentIndex++;
                }
                $(obj_).animate({ 'left': -currentIndex * wW }, function () {
                    $(obj_).find(".current").removeClass("current");
                    $(obj_).find(".slide-item").eq(currentIndex).addClass("current");
                    $(".top-nav-icons").find("li.current").removeClass("current");
                    $(".top-nav-icons li").eq(currentIndex).addClass("current");
                    $.pjax({ url: $(obj_).find(".slide-item").eq(currentIndex).attr("data-url"), container: "#" + $(obj_).find(".slide-item").eq(currentIndex).attr("id"), });
                });
                $(document).bind("end.pjax", function () {
                    $(obj_).parent().css({ "height": $(obj_).find(".slide-item").eq(currentIndex).height() });
                });
            } else {

            }
        });
        $(window).resize(function () {
            wW = $(window).width();
        });
    }
})(jQuery);

var HDMobile = {};

HDMobile.System = {
    guid: function () {
        return "LTMGUID__" + (this.guid._counter++).toString(36);
    }
};
HDMobile.System.guid._counter = 1;

HDMobile.Object = {
    isString: function (object) {
        return typeof object == 'string';
    },
    isNumber: function (object) {
        return typeof object == 'number';
    },
    extend: function () {
        var result = arguments[0] || {}, length = arguments.length;
        for (var i = 1; i < length; i++) {
            if (typeof arguments[i] == 'object') {
                for (var key in arguments[i]) {
                    if (arguments[length - 1] === true && arguments[i][key].constructor == Object) {
                        HDMobile.Object.extend(result[key], arguments[i][key]);
                    } else {
                        result[key] = arguments[i][key];
                    }
                }
            }
        }
        return result;
    }
};
HDMobile.Swap = {
    down: function (options) {
        options = HDMobile.Object.extend({
            content: null,
            callback: function () {
            },
            language: {
                loading: "正在加载...",
                complete: "向上拉动显示下$条",
                last: "",
                noresult: "未找到符合条件的结果"
            }
        }, options, true);
        options.content && options.content.each(function () {
            var This = $(this),
                ul = This.find("ul.swap-list");
            var languageTips = "",
                recordcount = ul.attr("data-lt-recordcount") || 0,
                pagesize = ul.attr("data-lt-pagesize") || 10;
            options.language.complete = options.language.complete.replace("$", pagesize);
            switch (Math.ceil(recordcount / pagesize)) {
                case 0:
                    languageTips = options.language.noresult;
                    This.attr("data-lt-suspend", true);
                    break;
                case 1:
                    ul.children("li").length > 0 && This.attr("data-lt-suspend", true);
                    break;
                default:
                    languageTips = options.language.complete;
            }
            $('<div class="page-tips">' + languageTips + '</div>').appendTo(This);
            var timeout = false;
            This.bind("swipemove",function () {
                if (timeout) {
                    clearTimeout(timeout);
                }
                var This = $(this);
                //if (This.jqmData("lt-loader")) return;
                var tips = This.find(".page-tips");
                //This.jqmData("lt-loader", true);
                //window.setTimeout(function () {
                var waiting = false;
                if (ul.attr("data-wait") != null && "1" == ul.attr("data-wait")) {
                    waiting = true;
                }
                if (waiting) {
                    return;
                }
                timeout = setTimeout(function () {
                    var page = parseInt(ul.attr("data-lt-page")) || 1;
                    if ($(window).scrollTop() + $(window).height() > tips.offset().top + 100 && !This.attr("data-lt-suspend")) {
                        $.ajax({
                            url: ul.attr("data-lt-url"),
                            type: "GET",
                            dataType: "json",
                            data: { "page": page + 1 },
                            success: function (data) {
                                if (data.flag == 1) {
                                    ul.attr("data-lt-page", page + 1);
                                    $(options.callback ? options.callback.call(options, data) : "").appendTo(ul);
                                    $(ul).closest(".slide-wrap").css({ "height": $(ul).parent().height() });
                                    window.setTimeout(function () {
                                        typeof data.totalPage != "undefined" && (page + 1 >= data.totalPage) && tips.html(options.language.last) && This.attr("data-lt-suspend", true);
                                    }, 100);
                                }
                                ul.removeAttr("data-wait");
                            },
                            beforeSend: function () {
                                ul.attr("data-wait", "1");
                                tips.html(options.language.loading);
                                //$.mobile.loading("show");
                            },
                            complete: function () {
                                //This.jqmRemoveData("lt-loader");
                                tips.html(languageTips);
                                ul.removeAttr("data-wait");
                                //$.mobile.loading("hide");
                            }
                        });
                    } else {
                        return;
                    }
                }, 500);
            }).bind("vmousedown", function () {
                    $(this).trigger("swipemove");
                });

        });
        return this;
    }
};


$(document).ready(function () {
    $.ajaxSetup({ cache: false });
    var dr = '';
    var r = dr ? dr : encodeURIComponent(document.referrer);
    var u = encodeURIComponent(window.location);

    $("#sharebtn").click(function () {
        $(".shadow_bg,.sharebox").show();
    });
    $(".shadow_bg,.sharebox,.follow-tip").click(function () {
        $(".shadow_bg,.sharebox,#QRCode-box,.follow-tip,.verification-send").hide();
    });
    $(".show-nav-btn a").click(function () {
        if ($("#nav_wrap").hasClass("on")) {
            $("#nav_wrap").removeClass("on").addClass("off");
        } else {
            $("#nav_wrap").removeClass("off").addClass("on");
        }
    });
    $("#nav_wrap a").click(function () {
        $("#nav_wrap").removeClass("on").addClass("off");
    });
});


function hgoback() {
    if (1 == window.history.length) {
        window.location = "/#wechat_redirect";
    }
    else {
        window.history.back();
    }
}

function hgobackto(go) {
    if (1 == window.history.length) {
        window.location = "/#wechat_redirect";
    }
    else {
        window.history.go(go);
    }
}