<?php global $cwd?>


    <?php if(isset($message)):?>
    <div id="message" class="updated below-h2">
        <p><?php echo $message?></p>
    </div>
    <?php endif;?>

    <h3>Available Records</h3>

    <?php
        //create array
        $cwd_records = array();
        foreach ($cwd->database->getAll() as $row) {
            $cwd_records[$row->ref] = array(
                                    'id' => $row->id,
                                    'ref' => $row->ref,
                                    'data' => $cwd->utility->processData($row->data),
                                        );
        }
    ?>

    <?php if(!empty($cwd_records)):?>
        <table class="wp-list-table widefat fixed posts" >
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Shortcode</th>
                    <th>Output</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 0; foreach ($cwd_records as $record) :?>
                    <tr class="cwd-tr-bg-color-<?php echo (int) ($count % 2); $count++ ?>">
                        <td>
                            <?php echo $record['ref'];?>
                            <div class="row-actions">
                                <span>
                                    <a href="<?php echo CWD_URL ?>&view=edit&id=<?php echo $record['id'];?>">Edit</a>
                                </span>
                                |
                                <span class="trash">
                                    <a  href="<?php echo CWD_URL ?>&view=delete&id=<?php echo $record['id'];?>">
                                        Delete
                                    </a>
                                </span>
                            </div>
                        </td>
                        <td>
                            <?php
                            $isString = (is_string($record['data']) && !$this->utility->isJson(stripcslashes($record['data'])));
                            ?>
                            <?php if($isString):?>
                                <span class="select">
                                    [cwd ref="<?php echo $record['ref'] ?>"]
                                </span>
                            <?php else:?>
                                <span class="arraydata">
                                    The data stored is an array.<br>
                                    Refer to the <a href="<?php echo CWD_URL ?>&view=user">user guide</a> for help.
                                </span>
                            <?php endif ?>
                        </td>


                        <?php if($isString):?>
                            <td>
                                <?php echo $record['data'] ?>
                            </td>
                        <?php else:?>

                            <?php
                            $data = $record['data'];

                            if(!is_array($data))
                            {

                                $data = $cwd->utility->objectToArray(json_decode(stripcslashes($data)));
                            }
                            ?>

                            <td class="cwd-td-array">
                                <?php $this->output->prettyArray($data) ?>
                            </td>

                        <?php endif ?>

                    </tr>
                    <?php endforeach;?>
            </tbody>
        <?php else:?>
            You don't have any records yet! Take a peek at the <a href="<?php echo CWD_URL;?>&view=user">user guide</a> to get started.
        <?php endif;?>

        </table>


