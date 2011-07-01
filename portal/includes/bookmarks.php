          <div id="bookmarks"> <a href="/bookmarks" class="link-oq">O que é isso?</a>
            <ul>
             <li id="b-twitter"><a href="http://twitter.com/home/?status=<?=urlencode(Util::bitly($conteudo['compartilhar']).' '.$titulo.' #iteia')?>">twitter</a></li>
               <li id="b-delicious"><a href="http://del.icio.us/post?url=<?=urlencode($conteudo['compartilhar'].' '.$titulo.' #iteia');?>">delicious</a></li>
			   <li id="b-facebook"><a href="http://www.facebook.com/share.php?u=<?=$conteudo['compartilhar'];?>" name="fb_share" type="icon_link" share_url="<?=$conteudo['compartilhar'];?>">facebook</a></li>
			  <?php /* 
			  <li id="b-facebook"><a href="http://www.facebook.com/share.php?u=<?=$conteudo['compartilhar'];?>">facebook</a></li>
			  <li id="b-yahoo"><a href="http://buzz.yahoo.com/buzz?targetUrl=<?=Util::bitly($conteudo['compartilhar']);?>">Yahoo buzz</a></li>
              <li id="b-digg"><a href="http://digg.com/submit?phase=2&amp;url=<?=Util::bitly($conteudo['compartilhar']);?>">digg it </a></li>
				   */?>
            </ul>
          </div>