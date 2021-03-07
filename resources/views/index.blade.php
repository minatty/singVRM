<!DOCTYPE html>
<html lang="jp">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>singVRM</title>
      <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="./css/main.css">
  </head>
  <body>
    <form id="fileForm" method="POST" action="/upload" enctype="multipart/form-data">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <div id="controlPanel" class="controlPanel">
        <div id="panelIcon">
          <i id="open" class="fas fa-chevron-right displayNone"></i>
          <i id="close" class="fas fa-chevron-left"></i>
        </div>
        <!-- ヘッダ / Header -->
        <div id="header">
          <!-- 楽曲セレクタ / Playback control -->
          楽曲選択
          <div id="control" class="far">
            <input id="songSelector" type="text" list="songList" size="60">
              <input id="start" type="button" value="start!">
              <datalist id="songList">
                  <option value="https://www.youtube.com/watch?v=ygY2qObZv24" lavel="愛されなくても君がいる - ピノキオピー">愛されなくても君がいる - ピノキオピー</option>
                  <option value="https://www.youtube.com/watch?v=a-Nf3QUFkOU" label="ブレス・ユア・ブレス - 和田たけあき feat. 初音ミク">ブレス・ユア・ブレス - 和田たけあき feat. 初音ミク</option>
                  <option value="https://www.youtube.com/watch?v=XSLhsjepelI" label="グリーンライツ・セレナーデ - Omoi">グリーンライツ・セレナーデ - Omoi</option>
              </datalist>
          </div>
          <div name="border"></div>
          <!-- 再生コントロール -->
          <div>
            <div id="control" class="far">
              <a href="#" id="backward" class="disabled"><i class="fas fa-backward"></i></a>
              <a href="#" id="play" class="disabled">&#xf144;</a>
              <a href="#" id="stop" class="disabled">&#xf28d;</a>
              <a href="#" id="forward" class="disabled"><i class="fas fa-forward"></i></a>
            </div>
            <div>
              <!-- ボリュームコントロール -->
              <input type="range" id="volumeControl" min="0" max="100">
            </div>
          </div>
          <div name="border"></div>
          <!-- 楽曲情報 -->
          <div id="meta">
            <div id="artist">artist: <span>-</span></div>
            <div id="song">song: <span>-</span></div>
          </div>
          <div name="border"></div>
          <!-- スコア表示領域 -->
          <span>score:</span>
          <div id="score">0</div>
          <div name="border"></div>
        </div>
        <!-- モデルオプション -->
        <div name="modelOptions">
          <div>
            <label id="chkShapeAnimateLabel" for="chkShapeAnimate">
              <input type="checkbox" id="chkShapeAnimate">口パクを滑らかにする
            </label>
          </div>
          <div>
            <label id="chkSwingArmLabel" for="chkSwingArm">
              <input type="checkbox" id="chkSwingArm">腕を動かす
            </label>
          </div>
          <!-- 口パクタイミング調整 -->
          <div>
            <div>口パクタイミングのディレイ幅</div>
            <input type="range" id="syncDelayTime" min="0" max="1000" value="200">
            <span id="currentDelayTime">-</span>[ms]
          </div>
          <!-- 歌詞の出現位置調整 -->
          <div id="mouthPositionAdjust">
            歌詞の出現位置調整
            <div id="X">X<input type="range" id="mouthPositionAdjustX" min="-100" max="100" value="0"><span id="currentAdjustValueX">0</span></div>
            <div id="Y">Y<input type="range" id="mouthPositionAdjustY" min="-100" max="100" value="0"><span id="currentAdjustValueY">0</span></div>
            <div id="Z">Z<input type="range" id="mouthPositionAdjustZ" min="-100" max="100" value="0"><span id="currentAdjustValueZ">0</span></div>
          </div>
        </div>
      </div>
      <!-- モデル選択 -->
      <span id="models">
          <input type="file" name="vrmfile" id="vrmfile">
          <div title="千駄ヶ谷篠"><img id="sendagaya-shino" name="models"/></div>
          <div title="しわミ"><img id="shiwa-model" name="models"/></div>
      </span>

      <!-- メディア表示 -->
      <div id="media"></div>
      <!-- 歌詞表示領域 -->
      <div id="lyrics"></div>
      <!-- VRM表示領域 -->
      <main id="screen"></main>
      <img id="backgroundImageMelo"/>
      <img id="backgroundImageSabi"/>
    </form>
  </body>

    <!-- CDN読み込み -->
    <script src="https://unpkg.com/three@0.106.2/build/three.js"></script>
    <script src="https://unpkg.com/three@0.106.2/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://unpkg.com/three@0.106.2/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@pixiv/three-vrm@0.4.3/lib/three-vrm.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/textalive-app-api/dist/index.js"></script>
    <script src="https://unpkg.com/webfontloader/webfontloader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- スクリプト読み込み -->
    <script src="{{ asset('/js/Vowels.js') }}"></script>
    <script src="{{ asset('/js/INKI_KJ.js') }}"></script>
    <script src="{{ asset('/js/BYB_KJ.js') }}"></script>
    <script src="{{ asset('/js/GLC_KJ.js') }}"></script>
    <script src="{{ asset('/js/VRMModel.js') }}"></script>
    <script src="{{ asset('/js/TextAlive.js') }}"></script>
    <script src="{{ asset('/js/LyricAnimations.js') }}"></script>
    <script src="{{ asset('/js/Score.js') }}"></script>
    <script src="{{ asset('/js/index.js') }}"></script>
</html>