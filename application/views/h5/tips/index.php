<div class="y_wrap">
          <div class="y_pages">
          <div class="y_page">
          <div id="contentList">
              <!--<div class="y_banner">
                   test1
              </div>-->
              <div class="y_section">
                    test2
              </div>
               <!-- end y_section -->
<?php foreach($tips as $tip){?>
              <div class="y_section">
                  <div class="y_suBcon">
                        <i class="m01 mps"></i>
                        <i class="m02">
                           <h1>
                              <?php echo date('Y-m-d H:i:s',$tip['create_time']);?>
                           </h1>
                        </i>
                      <div class="y_tit01">
                        <?php echo $tip['tips_message'];?>
                      </div>
                      <!-- <div class="y_con001">
                             test4
                      </div> -->
                      <div class="y_con01" style="<?php if($tip['status']=='nonstart'){ echo 'color:green';}elseif($tip['status'] =='start'){echo 'color:gray';} ?>">
                           <?php echo $tip['status'];?>
                      </div>
                      <!--
                      <div class="y_Xg">
                            test6
                      </div>
                      <ul class="y_list_news">
                          <li>
                              <div class="m1">test7</div>
                              <div class="m2">test8</div>
                          </li>
                      </ul>
                       -->
                  </div>
              </div>
              <!-- end y_section -->
<?php }?>
          <div class="y_section">
          <div class="y_suBcon">
          <div class="y_tit01">
          test3
          </div>
          <div class="y_con001">
          test4
          </div>
          <div class="y_con01">
          test5
          </div>
          <div class="y_Xg">
          test6
          </div>
          <ul class="y_list_news">
          <li>
          <div class="m1">test7</div>
          <div class="m2">test8</div>
          </li>
          </ul>
          </div>
          </div>
          <!-- end y_section -->

          </div>
          <!-- end contentList-->
          </div>

          </div>
</div>