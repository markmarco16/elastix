<div id="filterdiv">
    <form id="filterform">
        <input type="search" id="search" placeholder="Search">
            <select class="filter filter1">
                <option>MAIL</option>
                <option>FAX</option>
                <option>VOICE MAIL</option>
            </select>
            <select class="filter filter2">
                <option>ALL</option>
                <option>READ</option>
                <option>UNREAD</option>
                <option>STARRED</option>
                <option>UNSTARRED</option>
                <option>CONTACTS</option>
            </select>	
    </form>
</div>   

<div id="paneldiv">  
    <div id="leftdiv">
        <div id="b1_1">
            <div class="folder" onclick="create_showInbox();">Inbox</div>
            <div class="folder">Sent Mail</div>
            <div class="folder">Spam</div>
            <div class="folder">Trash</div>
            <div class="folder">Drafts</div>
            <div class="folder">Personal</div>
        </div>
    </div>
    <div id="centerdiv">
        <div id="b2_1">	
            <div id="paginationdiv">
                <div id="display1" class="color1 ra_disp1_10">
                    <div id="icn_disp1" class="cont_pic_tag ra_disp1_10" >
                        <span class="icn_d">ë</span>
                    </div>	
                </div>
            </div>
                                
            <div id="contentdiv">
                <div id="bodymail"></div>
                <div id="createmail"></div>
                <div id="table">
                    {section name=mail loop=$MAILS }
                    {if $MAILS[mail].status}
                        <div class="row" id={$MAILS[mail].status}{$MAILS[mail].UID} style="background-color:#ffff;">
                    {else}
                        <div class="row" id={$MAILS[mail].status}{$MAILS[mail].UID} style="background-color:rgb(229,229,229);">
                    {/if}
                            <div class="sel"><input type="checkbox" value={$MAILS[mail].UID} class="inp1" name="checkmail"/></div>	
                            <div class="ic">
                                <div class="icon"><img border="0" src="web/apps/home/images/mail2.png" class="icn_buz"></td></div>
                                <div class="star"><span class="st">e</span></div>	
                                <div class="trash"><span class="st">ç</span></div>	
                            </div>
                            <div class="from" onclick="view_body({$MAILS[mail].UID});"><span>{$MAILS[mail].from}</span></div> 
                            <div class="subject"><span>{$MAILS[mail].subject}</span></div> 
                            <div class="date"><span>{$MAILS[mail].date}</span></div> 
                        </div>
                    {/section}
                </div>
            </div>
        </div>
    </div>
</div>

