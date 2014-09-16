<?php if( $D->num_pages > 1 ) { ?> 
						<div class="paging" style="clear:both;">
							<div class="paging2">
								<span><?= $this->lang('paging_title') ?></span>
								<?php if($D->pg > 3) { ?>
								<a href="<?= $D->paging_url ?><?= $D->pg-1 ?>" class="pp"></a>
								<?php } ?>
								<!-- <span>...</span> -->
								<?php 
								if($D->pg <= 2) {
									$mn	= 1;
									$mx	= min(5, $D->num_pages);
								}
								elseif($D->pg >= $D->num_pages-2) {
									$mn = $D->num_pages - min(5, $D->num_pages) + 1;
									$mx = $D->num_pages;
								}
								else {
									$mn = $D->pg-2;
									$mx = $D->pg+2;
								}
								for($i=$mn; $i<=$mx; $i++) { ?>
								<a href="<?= ($this->user->is_logged)? $D->paging_url.$i : $C->SITE_URL.'signup'; ?>" class="<?= $i==$D->pg?'onpage':'' ?>"><b><?= $i ?></b></a>
								<?php } ?>
								<!-- <span>...</span> -->
								<?php if($D->pg < $D->num_pages-2) { ?>
								
								<a href="<?= ($this->user->is_logged)? $D->paging_url:$C->SITE_URL.'signup'; ?><?= $D->pg+1 ?>" class="np"></a>
								<?php } ?>
							</div>
						</div>
						<div class="klear"></div>
<?php } ?>