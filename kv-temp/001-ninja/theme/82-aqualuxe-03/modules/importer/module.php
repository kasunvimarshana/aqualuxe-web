<?php
// Importer admin page stub; full tool can be extended incrementally.
add_action( 'admin_menu', function(){
	// Choose parent: prefer AquaLuxe top-level, fallback to Tools if not present
	$parent = 'aqualuxe';
	$parent_exists = false;
	if ( function_exists('is_admin') && is_admin() ) {
		global $menu;
		if ( is_array($menu) ) {
			foreach ( $menu as $m ) { if ( isset($m[2]) && $m[2] === $parent ) { $parent_exists = true; break; } }
		}
	}
	$parent_slug = $parent_exists ? 'aqualuxe' : 'tools.php';

	add_submenu_page($parent_slug, call_user_func('__','Demo Importer','aqualuxe'), call_user_func('__','Demo Importer','aqualuxe'), 'manage_options', 'aqualuxe-importer', function(){
		if ( ! call_user_func('current_user_can','manage_options') ) return;
		echo '<div class="wrap"><h1>' . call_user_func('esc_html__','AquaLuxe Demo Importer','aqualuxe') . '</h1>';
		echo '<p>' . call_user_func('esc_html__','Create a complete demo with pages, CPTs, products, and menus. Use Flush to reset.','aqualuxe') . '</p>';
		echo '<form method="post" class="card" id="alx-import-form">';
		echo '<p><label><input type="checkbox" name="alx_flush" value="1"> ' . call_user_func('esc_html__','Flush existing content (dangerous)','aqualuxe') . '</label></p>';
		echo '<fieldset style="margin:1em 0;padding:0.5em 0;border-top:1px solid #ddd">';
		echo '<legend style="font-weight:600">' . call_user_func('esc_html__','Content to import','aqualuxe') . '</legend>';
		echo '<p><label><input type="checkbox" name="alx_pages" value="1" checked> ' . call_user_func('esc_html__','Pages','aqualuxe') . '</label></p>';
		echo '<p><label><input type="checkbox" name="alx_services" value="1" checked> ' . call_user_func('esc_html__','Services (CPT)','aqualuxe') . '</label></p>';
		echo '<p><label><input type="checkbox" name="alx_events" value="1" checked> ' . call_user_func('esc_html__','Events (CPT)','aqualuxe') . '</label></p>';
		echo '<p><label><input type="checkbox" name="alx_media" value="1" checked> ' . call_user_func('esc_html__','Media (images)','aqualuxe') . '</label></p>';
		echo '<p><label><input type="checkbox" name="alx_users" value="1" checked> ' . call_user_func('esc_html__','Users (demo roles)','aqualuxe') . '</label></p>';
		echo '<p><label><input type="checkbox" name="alx_classifieds" value="1" checked> ' . call_user_func('esc_html__','Classifieds (CPT)','aqualuxe') . '</label></p>';
		echo '<p><label><input type="checkbox" name="alx_widgets" value="1" checked> ' . call_user_func('esc_html__','Widgets (classic sidebars)','aqualuxe') . '</label></p>';
		$has_wc = class_exists('WooCommerce');
		$has_sidebars = ! empty($GLOBALS['wp_registered_sidebars']) && is_array($GLOBALS['wp_registered_sidebars']);
		$wc_attr = $has_wc ? '' : ' disabled title="' . call_user_func('esc_attr__','WooCommerce not active','aqualuxe') . '"';
		echo '<p><label><input type="checkbox" name="alx_products" value="1" checked' . $wc_attr . '> ' . call_user_func('esc_html__','WooCommerce demo products','aqualuxe') . ( $has_wc ? '' : ' <em>(' . call_user_func('esc_html__','requires WooCommerce','aqualuxe') . ')</em>' ) . '</label></p>';
		echo '<p><label><input type="checkbox" name="alx_menus" value="1" checked> ' . call_user_func('esc_html__','Menus','aqualuxe') . '</label></p>';
		$wg_attr = $has_sidebars ? '' : ' disabled title="' . call_user_func('esc_attr__','No registered sidebars found; widgets disabled','aqualuxe') . '"';
		echo '<p><label><input type="checkbox" name="alx_widgets" value="1" checked' . $wg_attr . '> ' . call_user_func('esc_html__','Widgets (classic sidebars)','aqualuxe') . ( $has_sidebars ? '' : ' <em>(' . call_user_func('esc_html__','no sidebars','aqualuxe') . ')</em>' ) . '</label></p>';
	echo '</fieldset>';
		echo '<p><label>' . call_user_func('esc_html__','Locale (for future i18n seeds):','aqualuxe') . ' <input name="alx_locale" value="en" /></label></p>';
		echo '<p><label><input type="checkbox" name="alx_dry" value="1"> ' . call_user_func('esc_html__','Dry run (no changes; async only)','aqualuxe') . '</label></p>';
		echo '<p><label><input type="checkbox" name="alx_schedule" value="1"> ' . call_user_func('esc_html__','Schedule run (hourly, WP-Cron)','aqualuxe') . '</label></p>';
		call_user_func('wp_nonce_field','aqualuxe_import');
		call_user_func('submit_button', call_user_func('__','Run Import','aqualuxe'), 'primary', 'alx-run-import');
		echo ' <button type="button" class="button" id="alx-run-import-async">' . call_user_func('esc_html__','Run Import (Async)','aqualuxe') . '</button>';
		echo ' <button type="button" class="button" id="alx-export-preview" style="margin-left:8px">' . call_user_func('esc_html__','Preview Current Content','aqualuxe') . '</button>';
		echo '</form>';
		echo '<div id="alx-import-progress" style="margin-top:1rem; display:none">'
		   . '<div class="notice inline"><p><strong>' . call_user_func('esc_html__','Progress','aqualuxe') . ':</strong> <span id="alx-progress-label">0%</span></p>'
		   . '<progress id="alx-progress-bar" max="100" value="0" style="width:320px"></progress> '
		   . '<button type="button" class="button" id="alx-download-log" style="margin-left:8px">' . call_user_func('esc_html__','Download log','aqualuxe') . '</button>'
		   . ' <button type="button" class="button" id="alx-cancel" style="margin-left:4px">' . call_user_func('esc_html__','Cancel','aqualuxe') . '</button>'
		   . '</div>'
		   . '<pre id="alx-progress-log" style="max-height:200px;overflow:auto;background:#111;color:#eee;padding:8px;border-radius:4px"></pre>'
		   . '</div>';
	// Inline nonce to avoid dependency on enqueued scripts
	echo '<script>window.alxImporterNonce = ' . wp_json_encode( wp_create_nonce('aqualuxe_import_step') ) . ';</script>';
		echo <<<'ALX'
<script>
(()=>{
 const $btn=document.getElementById("alx-run-import-async"); if(!$btn) return;
 const $form=document.getElementById("alx-import-form"); const $bar=document.getElementById("alx-progress-bar");
 const $label=document.getElementById("alx-progress-label"); const $log=document.getElementById("alx-progress-log"); const $wrap=document.getElementById("alx-import-progress"); const $dl=document.getElementById("alx-download-log"); const $cancel=document.getElementById("alx-cancel");
 const $preview=document.getElementById("alx-export-preview");
 let canceled=false;
 function log(m){ $log.textContent += (m+"\n"); $log.scrollTop=$log.scrollHeight; }
 function setp(p){ $bar.value=p; $label.textContent=p+"%"; }
 function selected(name){ const el=$form.querySelector(`[name="${name}"]`); return el && !el.disabled && el.checked; }
 function buildSteps(){ const base=[]; if(selected('alx_flush')) base.push("flush"); if(selected('alx_media')) base.push("media"); if(selected('alx_users')) base.push("users"); if(selected('alx_pages')) base.push("pages"); if(selected('alx_services')) base.push("services"); if(selected('alx_events')) base.push("events"); if(selected('alx_products')) base.push("products"); if(selected('alx_classifieds')) base.push("classifieds"); if(selected('alx_widgets')) base.push("widgets"); if(selected('alx_menus')) base.push("menus"); base.push("frontpage","finalize"); return base; }
 async function postStep(step){
	 const fd=new FormData($form); fd.append("action","aqualuxe_import_step"); fd.append("step",step); fd.append("_ajax_nonce",(window.alxImporterNonce||""));
	 const res=await fetch(ajaxurl,{method:"POST",credentials:"same-origin",body:fd});
	 if(!res.ok) throw new Error("HTTP "+res.status); const json=await res.json();
	 if(!json||!json.success) throw new Error(json && json.data ? json.data : "Failed");
	 return json.data;
 }
 async function run(){
	 canceled=false; $wrap.style.display="block"; setp(0); $btn.disabled=true;
	 const steps=buildSteps(); let i=0;
	 for(const s of steps){ if(canceled){ log("✋ Canceled by user"); break; }
		 log("Step: "+s+"...");
		 try{ const data=await postStep(s); const cb=data.counts_by_type||{}; log("✔ "+s+" ok (posts:"+data.counts.posts+", menuItems:"+data.counts.menu_items+")"); if(s==="finalize"){ log("Summary → pages:"+(cb.pages||0)+", services:"+(cb.services||0)+", events:"+(cb.events||0)+", products:"+(cb.products||0)+", classifieds:"+(cb.classifieds||0)+", media:"+(cb.media||0)+", users:"+(cb.users||0)+", widgets:"+(cb.widgets||0)+", menus:"+(cb.menus||0)); } }
		 catch(e){ log("✖ "+s+" failed: "+e.message); alert("Importer failed at step: "+s+"\n"+e.message); $btn.disabled=false; return; }
		 i++; setp(Math.round(i*100/steps.length));
	 }
	 $btn.disabled=false;
 }
 $btn.addEventListener("click", e=>{ e.preventDefault(); run(); });
 if($preview){ $preview.addEventListener("click", async()=>{
	 try{
		 const fd=new FormData(); fd.append("action","aqualuxe_export_preview"); fd.append("_ajax_nonce",(window.alxImporterNonce||""));
		 const res=await fetch(ajaxurl,{method:"POST",credentials:"same-origin",body:fd});
		 if(!res.ok) throw new Error("HTTP "+res.status);
		 const json=await res.json(); if(!json||!json.success) throw new Error(json&&json.data?json.data:"Failed");
		 const c=json.data.counts||{}; const lines=["Preview:",`pages: ${c.pages||0}`,`services: ${c.services||0}`,`events: ${c.events||0}`,`products: ${c.products||0}`,`classifieds: ${c.classifieds||0}`,`media: ${c.media||0}`,`users: ${c.users||0}`,`widgets: ${c.widgets||0}`,`menu items: ${c.menus||0}`];
		 $wrap.style.display="block"; $log.textContent = lines.join("\n");
	 }catch(e){ alert("Preview failed: "+e.message); }
 }); }
 if($dl){ $dl.addEventListener("click", ()=>{ const blob=new Blob([$log.textContent||""],{type:"text/plain"}); const url=URL.createObjectURL(blob); const a=document.createElement("a"); a.href=url; a.download="aqualuxe-import-log-"+new Date().toISOString().replace(/[:.]/g,'-')+".txt"; document.body.appendChild(a); a.click(); a.remove(); setTimeout(()=>URL.revokeObjectURL(url), 1000); }); }
 if($cancel){ $cancel.addEventListener("click", ()=>{ canceled=true; }); }
})();
</script></div>
ALX;

		// Rollback UI
		$ledger = call_user_func('get_option','alx_last_import_ledger');
		if ( ! empty( $ledger ) && is_array( $ledger ) ) {
			$ts = isset($ledger['ts']) ? (int)$ledger['ts'] : 0;
			echo '<hr/><h2>' . call_user_func('esc_html__','Rollback','aqualuxe') . '</h2>';
			if ( ! empty($ledger['counts']) && is_array($ledger['counts']) ) {
				$c = $ledger['counts'];
				echo '<p><strong>' . call_user_func('esc_html__','Last import summary:','aqualuxe') . '</strong> ' .
					call_user_func('esc_html', sprintf('Pages: %d, Services: %d, Events: %d, Products: %d, Classifieds: %d, Media: %d, Users: %d, Widgets: %d, Menu items: %d', (int)($c['pages']??0),(int)($c['services']??0),(int)($c['events']??0),(int)($c['products']??0),(int)($c['classifieds']??0),(int)($c['media']??0),(int)($c['users']??0),(int)($c['widgets']??0),(int)($c['menus']??0) ) ) . '</p>';
			}
			echo '<p>' . call_user_func('esc_html__','A ledger from the last import exists. You can rollback posts and menu items created in that run.','aqualuxe') . '</p>';
			echo '<p><small>' . call_user_func('esc_html__','Last import: ','aqualuxe') . ( $ts ? call_user_func('esc_html', call_user_func('date_i18n','Y-m-d H:i', $ts) ) : '' ) . '</small></p>';
			echo '<form method="post">';
			call_user_func('wp_nonce_field','aqualuxe_import_rollback');
			echo '<input type="hidden" name="alx_action" value="rollback" />';
			call_user_func('submit_button', call_user_func('__','Rollback last import','aqualuxe'), 'delete');
			echo '</form>';
		}
		echo '</div>';
	});
}, 20);

add_action('admin_init', function(){
	if ( ! call_user_func('current_user_can','manage_options') ) return;

	// Rollback handler
	if ( isset($_POST['alx_action']) && $_POST['alx_action'] === 'rollback' ) {
		if ( isset($_POST['_wpnonce']) && call_user_func('wp_verify_nonce', $_POST['_wpnonce'], 'aqualuxe_import_rollback') ) {
			$ledger = call_user_func('get_option','alx_last_import_ledger');
			if ( is_array($ledger) ) {
				// Delete posts
				if ( ! empty($ledger['posts']) && is_array($ledger['posts']) ) {
					foreach ( $ledger['posts'] as $pid ) { call_user_func('wp_delete_post', (int)$pid, true ); }
				}
				// Delete menu items
				if ( ! empty($ledger['menu_items']) && is_array($ledger['menu_items']) ) {
					foreach ( $ledger['menu_items'] as $mid ) { call_user_func('wp_delete_post', (int)$mid, true ); }
				}
				// Optionally delete created menu term
				if ( ! empty($ledger['menu_id']) ) {
					call_user_func('wp_delete_nav_menu', (int)$ledger['menu_id'] );
				}
				// Remove demo widgets we added (marked with aqlx_demo=1)
				$to_remove = [];
				foreach ( [ 'text', 'recent-posts' ] as $base ) {
					$opt_key = 'widget_' . $base;
					$opts = call_user_func('get_option', $opt_key, []);
					if ( is_array($opts) ) {
						foreach ( $opts as $idx => $cfg ) {
							if ( is_numeric($idx) && is_array($cfg) && ! empty($cfg['aqlx_demo']) ) {
								unset($opts[$idx]);
								$to_remove[] = $base . '-' . (int)$idx;
							}
						}
						call_user_func('update_option', $opt_key, $opts);
					}
				}
				if ( ! empty($to_remove) ) {
					$sidebars = call_user_func('get_option','sidebars_widgets', []);
					if ( is_array($sidebars) ) {
						foreach ( $sidebars as $sid => $arr ) {
							if ( $sid === 'wp_inactive_widgets' ) continue;
							if ( is_array($arr) ) { $sidebars[$sid] = array_values( array_diff( $arr, $to_remove ) ); }
						}
						call_user_func('update_option','sidebars_widgets', $sidebars);
					}
				}
				call_user_func('delete_option','alx_last_import_ledger');
				add_action('admin_notices', function(){ echo '<div class="notice notice-success"><p>' . call_user_func('esc_html__','Rollback completed.','aqualuxe') . '</p></div>'; });
			}
		}
		return; // Stop further processing
	}

	// Import handler
	if ( ! isset($_POST['_wpnonce']) || ! call_user_func('wp_verify_nonce', $_POST['_wpnonce'], 'aqualuxe_import') ) return;
	$opts = [
		'flush'    => isset($_POST['alx_flush']) ? 1 : 0,
		'media'    => isset($_POST['alx_media']) ? 1 : 0,
		'users'    => isset($_POST['alx_users']) ? 1 : 0,
		'pages'    => isset($_POST['alx_pages']) ? 1 : 0,
		'services' => isset($_POST['alx_services']) ? 1 : 0,
		'events'   => isset($_POST['alx_events']) ? 1 : 0,
		'products' => isset($_POST['alx_products']) ? 1 : 0,
		'classifieds' => isset($_POST['alx_classifieds']) ? 1 : 0,
		'widgets'  => isset($_POST['alx_widgets']) ? 1 : 0,
		'menus'    => isset($_POST['alx_menus']) ? 1 : 0,
		'locale'   => call_user_func('sanitize_text_field', $_POST['alx_locale'] ?? 'en' ),
	];
	require_once call_user_func('get_template_directory') . '/inc/Importer/Importer.php';
	$imp = new \AquaLuxe\Importer\Importer();
	$imp->run( $opts );
	// Clear any partial state from previous async runs; Importer::run already persisted the final ledger
	delete_option('alx_last_import_partial');

	// Optional scheduling via WP-Cron when checkbox is set
	if ( isset($_POST['alx_schedule']) ) {
		$opts_sched = $opts; $opts_sched['flush'] = 0; // never flush on schedule
		update_option('alx_import_schedule_opts', $opts_sched);
		if ( ! wp_next_scheduled('aqualuxe_import_cron') ) {
			wp_schedule_event( time() + 300, 'hourly', 'aqualuxe_import_cron' );
		}
		add_action('admin_notices', function(){ echo '<div class="notice notice-info"><p>' . esc_html__('Importer scheduled hourly. Flush is disabled for safety.','aqualuxe') . '</p></div>'; });
	} else {
		$ts = wp_next_scheduled('aqualuxe_import_cron');
		if ( $ts ) { wp_unschedule_event( $ts, 'aqualuxe_import_cron' ); }
		delete_option('alx_import_schedule_opts');
	}
	add_action('admin_notices', function(){ echo '<div class="notice notice-success"><p>' . call_user_func('esc_html__','AquaLuxe demo content imported.','aqualuxe') . '</p></div>'; });
});

// Inject a nonce for AJAX importer when on importer page
add_action('admin_enqueue_scripts', function(){
	if ( isset($_GET['page']) && $_GET['page'] === 'aqualuxe-importer' ) {
		wp_add_inline_script('jquery', 'window.alxImporterNonce = ' . wp_json_encode( wp_create_nonce('aqualuxe_import_step') ) . ';', 'after');
	}
});

// Cron event handler to perform scheduled imports
add_action('aqualuxe_import_cron', function(){
	$opts = get_option('alx_import_schedule_opts');
	if ( ! is_array($opts) ) return;
	require_once get_template_directory() . '/inc/Importer/Importer.php';
	$imp = new \AquaLuxe\Importer\Importer();
	$imp->run( $opts );
});

// AJAX step runner for progressive import
add_action('wp_ajax_aqualuxe_import_step', function(){
	if ( ! current_user_can('manage_options') ) wp_send_json_error('forbidden', 403);
	check_ajax_referer('aqualuxe_import_step');
	$step = isset($_POST['step']) ? sanitize_text_field($_POST['step']) : '';
	$opts = [
		'flush'    => isset($_POST['alx_flush']) ? 1 : 0,
		'media'    => isset($_POST['alx_media']) ? 1 : 0,
		'users'    => isset($_POST['alx_users']) ? 1 : 0,
		'pages'    => isset($_POST['alx_pages']) ? 1 : 0,
		'services' => isset($_POST['alx_services']) ? 1 : 0,
		'events'   => isset($_POST['alx_events']) ? 1 : 0,
		'products' => isset($_POST['alx_products']) ? 1 : 0,
		'classifieds' => isset($_POST['alx_classifieds']) ? 1 : 0,
		'widgets'  => isset($_POST['alx_widgets']) ? 1 : 0,
		'menus'    => isset($_POST['alx_menus']) ? 1 : 0,
		'locale'   => sanitize_text_field($_POST['alx_locale'] ?? 'en'),
		'dry'      => isset($_POST['alx_dry']) ? 1 : 0,
	];
	require_once get_template_directory() . '/inc/Importer/Importer.php';
	$imp = new \AquaLuxe\Importer\Importer();
	$partial = get_option('alx_last_import_partial') ?: [];
	if ( is_array($partial) ) { $imp->init_from_ledger($partial); }
	// Dry run: execute read-only steps and skip destructive writes/persistence
	$data = $imp->run_step($step, $opts);
	if ( empty($opts['dry']) ) {
		$ledger = $imp->get_ledger();
		if ( $step === 'finalize' ) {
			update_option('alx_last_import_ledger', $ledger);
			delete_option('alx_last_import_partial');
		} else {
			update_option('alx_last_import_partial', $ledger);
		}
	}
	wp_send_json_success($data);
});

// Minimal JSON export (preview) endpoint — register at top level (not inside another handler)
add_action('wp_ajax_aqualuxe_export_preview', function(){
	if ( ! current_user_can('manage_options') ) wp_send_json_error('forbidden', 403);
	check_ajax_referer('aqualuxe_import_step');
	$snapshot = [
		'ts' => time(),
		'counts' => [
			'pages' => wp_count_posts('page')->publish ?? 0,
			'services' => post_type_exists('service') ? ( wp_count_posts('service')->publish ?? 0 ) : 0,
			'events' => post_type_exists('event') ? ( wp_count_posts('event')->publish ?? 0 ) : 0,
			'classifieds' => post_type_exists('classified') ? ( wp_count_posts('classified')->publish ?? 0 ) : 0,
			'products' => class_exists('WooCommerce') ? ( wp_count_posts('product')->publish ?? 0 ) : 0,
			'media' => (int) ( wp_count_posts('attachment')->inherit ?? 0 ),
			'users' => count_users()['total_users'] ?? 0,
			'widgets' => ( function(){ $s = get_option('sidebars_widgets', []); if(!is_array($s)) return 0; $cnt=0; foreach($s as $k=>$arr){ if($k==='wp_inactive_widgets') continue; if(is_array($arr)) $cnt+=count($arr); } return $cnt; } )(),
		],
	];
	wp_send_json_success($snapshot);
});
