                /**
                 * 高亮suggest标题
                 * kw 输入关键词
                 * str 标题
                 * @2010-11-3
                 */
                function fixTopic(kw, str)
                {
                        if(kw.length==0 || str.length==0)
                                return;
                        var left_limit = '<span class="'+ options.matchClass +'">', right_limit = '</span>';
                        var topic = str;
                        var pattern = /([^\s])+/g;
                        var kws = kw.match(pattern);
                        var topic_words = topic.match(pattern);
                        for(var m=0; m< topic_words.length; m++)
                        {
                                for(var i=0; i < kws.length; i++)
                                {
                                        var re = eval("/^"+kws[i]+"/i");
                                        topic_words[m] = topic_words[m].replace(re, function(q){return '<{'+q+'}>'});
                                }
                        }
                        topic = topic_words.join(' ');
                        topic = topic.replace(/<{/g, left_limit);
                        topic = topic.replace(/}>/g, right_limit);
                        return topic + '<input type="hidden" value="'+ str +'" />';
                }