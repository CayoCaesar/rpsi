<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>

    <head>
        <title>Paging Example-User Listing</title>
    </head>
     
    <body>
        <div class="container">
            <h1 id='form_head'>User Listing</h1>
 
            <?php if (isset($results)) { ?>
                <table border="1" cellpadding="0" cellspacing="0">
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>Apellidos</th>
                        
                    </tr>
                     
                    <?php foreach ($results as $data) { ?>
                        <tr>
                            <td><?php echo $data->id ?></td>
                            <td><?php echo $data->nombre ?></td>
                             <td><?php echo $data->apellido ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <div>No user(s) found.</div>
            <?php } ?>
 
            <?php if (isset($links)) { ?>
                <?php echo $links ?>
            <?php } ?>
        </div>
    </body>
