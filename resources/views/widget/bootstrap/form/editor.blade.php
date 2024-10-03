@if(false)
    {{-- paste in top view--}}
    <div class="editor_div">
        @if(!empty($model->contents))
            @foreach ($model->contents as $i => $modelContent)
                @include('widget.mdl.form.editor', ['prefix' => '_'.$i, 'text' => $modelContent])
                @if($i == 0)
                    <hr>
                @endif
                <div>
                    <div style="cursor: pointer;" onclick="viewPresenter.show(this, ',.hide');viewPresenter.hide(this)">+</div>
                    <div style="display: none" class="hide">
                        @include('dashboard.article.editor', ['prefix' => '_'.$i.'_new', 'text' => '<p></p>'])
                    </div>
                </div>
            @endforeach
            @if(count($model->contents) <= 1)
                @include('widget.mdl.form.editor', ['prefix' => '_'.($i + 1), 'text' => '<p></p>'])
            @endif
        @else
            @include('widget.mdl.form.editor', ['prefix' => '_0', 'text' => '<p></p>'])
            <hr>
            @include('widget.mdl.form.editor', ['prefix' => '_1', 'text' => '<p></p>'])
        @endif
    </div>
    <button class="mdl-button mdl-button--accent mdl-button--raised" onclick="getContents()"> SAVE </button>
    {{-- end past --}}
@endif

<div class="editor widget">
    <div class="toolBar1">
        <select onchange="formatDoc{{ $prefix }}('formatblock',this[this.selectedIndex].value);this.selectedIndex=0;">
            <option selected>- формат -</option>
            <option value="h2">Title 2 &lt;h2&gt;</option>
            <option value="h3">Title 3 &lt;h3&gt;</option>
            <option value="h4">Title 4 &lt;h4&gt;</option>
            <option value="h5">Title 5 &lt;h5&gt;</option>
            <option value="h6">Подзаголовок &lt;h6&gt;</option>
            <option value="p">Параграф &lt;p&gt;</option>
        </select>
        <img class="intLink" title="Назад" onclick="formatDoc{{ $prefix }}('undo');" src="data:image/gif;base64,R0lGODlhFgAWAOMKADljwliE33mOrpGjuYKl8aezxqPD+7/I19DV3NHa7P///////////////////////yH5BAEKAA8ALAAAAAAWABYAAARR8MlJq7046807TkaYeJJBnES4EeUJvIGapWYAC0CsocQ7SDlWJkAkCA6ToMYWIARGQF3mRQVIEjkkSVLIbSfEwhdRIH4fh/DZMICe3/C4nBQBADs=" />
        <img class="intLink" title="Вперёд" onclick="formatDoc{{ $prefix }}('redo');" src="data:image/gif;base64,R0lGODlhFgAWAMIHAB1ChDljwl9vj1iE34Kl8aPD+7/I1////yH5BAEKAAcALAAAAAAWABYAAANKeLrc/jDKSesyphi7SiEgsVXZEATDICqBVJjpqWZt9NaEDNbQK1wCQsxlYnxMAImhyDoFAElJasRRvAZVRqqQXUy7Cgx4TC6bswkAOw==" />
        <img class="intLink" title="Удалить форматирование" onclick="formatDoc{{ $prefix }}('removeFormat')" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABGdBTUEAALGPC/xhBQAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAAOxAAADsQBlSsOGwAAAAd0SU1FB9oECQMCKPI8CIIAAAAIdEVYdENvbW1lbnQA9syWvwAAAuhJREFUOMtjYBgFxAB501ZWBvVaL2nHnlmk6mXCJbF69zU+Hz/9fB5O1lx+bg45qhl8/fYr5it3XrP/YWTUvvvk3VeqGXz70TvbJy8+Wv39+2/Hz19/mGwjZzuTYjALuoBv9jImaXHeyD3H7kU8fPj2ICML8z92dlbtMzdeiG3fco7J08foH1kurkm3E9iw54YvKwuTuom+LPt/BgbWf3//sf37/1/c02cCG1lB8f//f95DZx74MTMzshhoSm6szrQ/a6Ir/Z2RkfEjBxuLYFpDiDi6Af///2ckaHBp7+7wmavP5n76+P2ClrLIYl8H9W36auJCbCxM4szMTJac7Kza////R3H1w2cfWAgafPbqs5g7D95++/P1B4+ECK8tAwMDw/1H7159+/7r7ZcvPz4fOHbzEwMDwx8GBgaGnNatfHZx8zqrJ+4VJBh5CQEGOySEua/v3n7hXmqI8WUGBgYGL3vVG7fuPK3i5GD9/fja7ZsMDAzMG/Ze52mZeSj4yu1XEq/ff7W5dvfVAS1lsXc4Db7z8C3r8p7Qjf///2dnZGxlqJuyr3rPqQd/Hhyu7oSpYWScylDQsd3kzvnH738wMDzj5GBN1VIWW4c3KDon7VOvm7S3paB9u5qsU5/x5KUnlY+eexQbkLNsErK61+++VnAJcfkyMTIwffj0QwZbJDKjcETs1Y8evyd48toz8y/ffzv//vPP4veffxpX77z6l5JewHPu8MqTDAwMDLzyrjb/mZm0JcT5Lj+89+Ybm6zz95oMh7s4XbygN3Sluq4Mj5K8iKMgP4f0////fv77//8nLy+7MCcXmyYDAwODS9jM9tcvPypd35pne3ljdjvj26+H2dhYpuENikgfvQeXNmSl3tqepxXsqhXPyc666s+fv1fMdKR3TK72zpix8nTc7bdfhfkEeVbC9KhbK/9iYWHiErbu6MWbY/7//8/4//9/pgOnH6jGVazvFDRtq2VgiBIZrUTIBgCk+ivHvuEKwAAAAABJRU5ErkJggg==">
        <img class="intLink" title="Жирный" onclick="formatDoc{{ $prefix }}('bold');" src="data:image/gif;base64,R0lGODlhFgAWAID/AMDAwAAAACH5BAEAAAAALAAAAAAWABYAQAInhI+pa+H9mJy0LhdgtrxzDG5WGFVk6aXqyk6Y9kXvKKNuLbb6zgMFADs=" />
        <img class="intLink" title="Italic" onclick="formatDoc{{ $prefix }}('italic');" src="data:image/gif;base64,R0lGODlhFgAWAKEDAAAAAF9vj5WIbf///yH5BAEAAAMALAAAAAAWABYAAAIjnI+py+0Po5x0gXvruEKHrF2BB1YiCWgbMFIYpsbyTNd2UwAAOw==" />
        <img class="intLink" title="Пунктирный список" onclick="formatDoc{{ $prefix }}('insertunorderedlist');" src="data:image/gif;base64,R0lGODlhFgAWAMIGAAAAAB1ChF9vj1iE33mOrqezxv///////yH5BAEAAAcALAAAAAAWABYAAAMyeLrc/jDKSesppNhGRlBAKIZRERBbqm6YtnbfMY7lud64UwiuKnigGQliQuWOyKQykgAAOw==" />
        <span><input id="switchBox{{ $prefix }}" class="switchBox" type="checkbox" onchange="setDocMode{{ $prefix }}(this.checked);" /> <label for="switchBox{{ $prefix }}">Показать HTML</label></span>
    </div>
    <div id="editable_block{{ $prefix }}" class="editable_block" contenteditable="true">
        {!! $text !!}
    </div>
</div>

@if(empty($isAjax)) @push('js') @endif
    <script>

        var oDoc{{ $prefix }};

        function initDoc{{ $prefix }}() {
            oDoc{{ $prefix }} = document.getElementById("editable_block{{ $prefix }}");
            if (document.getElementById('switchBox{{ $prefix }}').checked) { setDocMode{{ $prefix }}(true); }
        }

        function formatDoc{{ $prefix }}(sCmd, sValue) {
            if (validateMode{{ $prefix }}()) { document.execCommand(sCmd, false, sValue); oDoc{{ $prefix }}.focus(); }
        }

        function validateMode{{ $prefix }}() {
            if (!document.getElementById('switchBox{{ $prefix }}').checked) { return true ; }
            alert("Uncheck \"Показать HTML\".");
            oDoc{{ $prefix }}.focus();
            return false;
        }

        function setDocMode{{ $prefix }}(bToSource) {
            var oContent;
            if (bToSource) {
                oContent = document.createTextNode(oDoc{{ $prefix }}.innerHTML);
                oDoc{{ $prefix }}.innerHTML = "";
                var oPre = document.createElement("p");
                oDoc{{ $prefix }}.contentEditable = false;
                oPre.contentEditable = true;
                oPre.appendChild(oContent);
                oDoc{{ $prefix }}.appendChild(oPre);
                document.execCommand("defaultParagraphSeparator", false, "p");
            } else {
                if (document.all) {
                    oDoc{{ $prefix }}.innerHTML = oDoc{{ $prefix }}.innerText;
                } else {
                    oContent = document.createRange();
                    oContent.selectNodeContents(oDoc{{ $prefix }}.firstChild);
                    oDoc{{ $prefix }}.innerHTML = oContent.toString();
                }
                oDoc{{ $prefix }}.contentEditable = true;
            }
            oDoc{{ $prefix }}.focus();
        }

        initDoc{{ $prefix }}();
    </script>
@if(empty($isAjax)) @endpush @endif

@if(\App\Application::pushLoad('editor_div'))
    @if(empty($isAjax)) @push('js') @endif
        <script>
            function getContents() {
                var contents = [];
                $('.editor_div div.editable_block').each(function () {
                    contents.push($(this).html().trim());
                });
                return contents;
            }
        </script>
    @if(empty($isAjax)) @endpush @endif
    @if(empty($isAjax)) @push('css_1') @endif
        <style>
            .editor .intLink { cursor: pointer; }
            .editor img.intLink { border: 0; }
            .editor .toolBar1 select { font-size:10px; background-color: #eff;}
            .editor .switchBox, .editor label { cursor: pointer; }

            .editor_div{
                width: 100%;
            }
            .editor_div .editable_block{
                background-color: #f0f0f0;
            }
            .editor_div div.editable_block{
                margin-bottom: 24px;
            }

            .editor_div div.editable_block{
                font-size: 11px;
                line-height: 13px;
            }
            .editor_div div.editable_block p{
                font-size: 14px;
                line-height: 18px;
                padding: 8px 0;
                min-height: 24px;
            }

            .editor_div div.editable_block h2,
            .editor_div div.editable_block h3,
            .editor_div div.editable_block h4,
            .editor_div div.editable_block h6,
            .editor_div div.editable_block h5{
                margin: 0;
                background-color: rgba(0,0,0,.1);
            }

            .editor_div div.editable_block h2{
                font-size: 28px;
                line-height: 32px;
            }
            .editor_div div.editable_block h3{
                font-size: 20px;
                line-height: 24px;
            }
            .editor_div div.editable_block h4{
                font-size: 16px;
                line-height: 18px;
            }
            .editor_div div.editable_block h5,
            .editor_div div.editable_block h6{
                font-size: 14px;
                line-height: 18px;
            }



            .editor.widget .editable_block{
                background-color: #f0f0f0;
            }
            .editor.widget div.editable_block{
                margin-bottom: 24px;
            }

            .editor.widget div.editable_block{
                font-size: 11px;
                line-height: 13px;
            }
            .editor.widget div.editable_block p{
                font-size: 14px;
                line-height: 18px;
                padding: 8px 0;
                min-height: 24px;
            }

            .editor.widget div.editable_block h2,
            .editor.widget div.editable_block h3,
            .editor.widget div.editable_block h4,
            .editor.widget div.editable_block h6,
            .editor.widget div.editable_block h5{
                margin: 0;
                background-color: rgba(0,0,0,.1);
            }

            .editor.widget div.editable_block h2{
                font-size: 28px;
                line-height: 32px;
            }
            .editor.widget div.editable_block h3{
                font-size: 20px;
                line-height: 24px;
            }
            .editor.widget div.editable_block h4{
                font-size: 16px;
                line-height: 18px;
            }
            .editor.widget div.editable_block h5,
            .editor.widget div.editable_block h6{
                font-size: 14px;
                line-height: 18px;
            }

            .editor.widget .intLink { cursor: pointer; }
            .editor.widget img.intLink { border: 0; }
            .editor.widget .toolBar1 select { font-size:10px; background-color: #eff;}
            .editor.widget .switchBox, .editor.widget label { cursor: pointer; }
        </style>
    @if(empty($isAjax)) @endpush @endif
@endif
