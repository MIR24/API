<?php

Route::get('smart/v1/categories','CategoryController@show')->middleware('cors');

Route::get('smart/v1/channels','ChannelsController@show')->middleware('cors');

Route::get('smart/v1/archives','ArchiveController@show')->middleware('cors');