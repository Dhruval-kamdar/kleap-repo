<div class="tutor-attachments-wrap">
    <?php
        $title_length = 18;

        foreach($materials_array as $attachment){
            $content = ($attachment->driveFile ? ($attachment->driveFile->driveFile ?? null) : null) ?? $attachment->youtubeVideo ?? $attachment->link ?? null;

            if(!$content){
                continue;
            }

            ?>
                <a href="<?php echo ($content->alternateLink ?? $content->url); ?>" target="_blank" class="tutor-gc-material">
                    <div style="background-image:url(<?php echo TUTOR_GC()->url; ?>/assets/images/attachment-icon.svg)" data-thumbnail_url="<?php echo $content->thumbnailUrl; ?>" class="tutor-gc-google-thumbnail">
                    
                    </div>
                    <div>
                        <span>
                            <?php 

                                $title = $content->title; 
                                $cutted_title = substr($title, 0, $title_length);
                                
                                echo $cutted_title, (strlen($title)>$title_length ? '..' : '');
                            ?>
                        </span>
                    </div>
                </a>
            <?php
        }
    ?>
</div>