/*
 音效
 jslover@20150512
 */
define(function (require, exports, module) {
    var $ = require('zepto');
    var stopMusic = false;
    var srcList = {
        'win': baseUrl + 'style/images/win.mp3'
        , 'lost': baseUrl + 'style/images/fail.mp3'
        , 'hit': baseUrl + 'style/images/hit.mp3'
        , 'bg': baseUrl + 'style/images/bg.mp3'
    }
    var bgTimer = 0;
    var Music = {
        $btn: $('.btn-audio')
        , audioBg: {}
        , audioSound: {}
        , bg: {
            play: function () {
                if (!stopMusic) {
                    Music.audioBg.play();
                }
            }
            , stop: function () {
                clearTimeout(bgTimer);
                Music.audioBg.pause();
            }
        }
        , sound: {
            play: function (type) {
                if (!stopMusic) {
                    clearTimeout(bgTimer);
                    if (type == 'hit') {
                        if (Music.audioHit.currentTime) {
                            Music.audioHit.currentTime = 1;
                        }
                        Music.audioHit.play();
                        bgTimer = setTimeout(function () {
                            Music.bg.play();
                        }, 500);
                    } else {
                        Music.bg.stop();
                        Music.audioSound.src = srcList[type];
                        Music.audioSound.play();
                    }
                }
            }
            , stop: function () {
                Music.audioSound.pause();
            }
        }
        , mute: function (isMute) {
            if (isMute) {
                this.$btn.addClass('on');
                this.audioBg.volume = 0.7;
                this.audioSound.volume = 1;
                this.audioBg.play();
                stopMusic = false;
            } else {
                this.$btn.removeClass('on');
                this.audioBg.volume = 0;
                this.audioSound.volume = 0;
                stopMusic = true;
                this.audioBg.pause();
                this.audioSound.pause();

            }
        }
        , init: function () {
            this.audioBg = document.getElementById('audio-bg');
            this.audioSound = document.getElementById('audio-sound');
            this.audioHit = document.getElementById('audio-hit');
            this.audioBg.src = srcList['bg'];
            this.audioSound.src = srcList['hit'];
            this.audioBg.pause();
            this.audioSound.pause();
        }
    };
    $(function () {
        Music.init();
    });
    module.exports = Music;
});