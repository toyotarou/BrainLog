<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;

class TarotMsgGet extends Command
{

    protected $signature = 'TarotMsgGet';

    protected $description = 'TarotMsgGet';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $str = "
        
big00	何にも縛られず無限の自由を謳歌する	着の身着のまま、身軽な荷物でふらふらと歩く旅人。片手には摘んだ花を持ち、存分に自由を謳歌していることが見て取れます。ただし、足の向かう先に道はありません。犬が危険を忠告していますが、気づく様子はなく…。そんな「愚者」のカードは自由さと同時に、一歩先がどうなるかわからない未知な状態を表しています。無限の可能性に溢れていますが、崖から転落するかもしれません。それでも、「なるようになる」と楽観的な状態を示すカードです。
big01	若く自信に溢れたクリエイティビティ	意気揚々と魔術のワンドを掲げる男性。自信に満ちた表情で能力をアピールしようとしています。テーブルの上にはワンド、ペンタクル、ソード、カップという、宇宙を構成する4つの要素が並べられています。彼はそれらすべてを自由自在に操る力を持つ「魔術師」。この力をもってすれば、どんなものも生み出すことができます。準備が万端に整い、いよいよアクションを起こすべき時がきたことを知らせるカードでもあります。
big02	緊張感すら漂う清廉で高貴な知性	地母神の冠を頂いた神秘的な女性、彼女は「女司祭」です。穢れのない高貴な雰囲気で、手にした聖典からも、彼女が思慮深く聡明な人物であることが窺えるでしょう。印象的なのは、背後にある白と黒の柱。これは男女、陰陽、光と闇、意識と無意識など、相反する2つのものを表しており、カード全体に緊張感をもたらしています。男女の性を表すシュロとザクロに背を向ける姿も、このカードが持つ高潔さを表しているでしょう。
big03	愛と美と実り、そのすべてを味わう女性	豊かに実る自然の中に、ゆったりと座る「女帝」。ふくよかな彼女の様子は妊娠を思わせます。大地が植物を育み、実りをもたらすように、人間もまた新たな生命を産み出す存在。その絶え間なく続く生命のサイクルと、そこから生まれる豊かさを象徴するカードと言えるでしょう。彼女のドレスに描かれているのは女性性の象徴であるザクロ。すべてを受け入れる包容力と、見返りを求めずに与える愛が、繁栄をもたらします。
big04	野心と統率力で組織のトップに立つ者	甲冑をまとい、玉座に堂々と座る男性。彼は一国の主です。人が集まれば、そこには社会が生まれ、統率する人が必要になります。幾多の争いを経て、実力と経験を認められたのが彼ということ。玉座の羊のシンボルは12星座のトップバッター牡羊座に通じ、野心や闘争心、リーダーシップが旺盛であることを表します。ちなみに、人類が作り上げた社会は「皇帝」、ありのままの自然は「女帝」という対比を覚えておくとよいでしょう。
big05	ルールとモラルを教える精神的なお手本	三位一体を表す三重の十字架を持ち、ふたりの神父に祝福を与える「司祭」が描かれています。彼は道徳を教え、生きる道を説く存在であり、多くの人からの尊敬を集めています。国において実務を担うのが「皇帝」なら、精神面をまとめるのが「司祭」と言えるでしょう。人としての生き方のお手本であると同時に、悩み苦しんでいる人に救いの手を差し伸べる存在でもあります。揺るぎない信頼と、精神的な拠り所を意味するカードです。
big06	ときめきと喜び、快楽にその身を委ねる	エデンの園にいる男女が天使の祝福を受けています。無防備な裸であることから、恐れるものは何もないことがわかります。気候は穏やかで、数々の果物の実る楽園で至福の時を満喫しているうちに、男女は次第にお互いの存在に魅了され、肉体的に深く結ばれたのです。すべてに満たされた、至福の心地よさを表すカードです。ただし、ここはエデン。蛇の誘惑によって、禁じられた実に手を伸ばし、楽園を追われる身になるというエピソードもあることを忘れないで。
big07	目的に向けてまっすぐに突き進む強さ	目指すべき道を見据えた戦士が「戦車」に乗っています。白と黒、2頭のスフィンクスは異なる衝動を表し、気を抜くと、全く違う方向へ走り出してしまうでしょう。そんな「戦車」を手綱なしでコントロールするには、相当な意志とパワーが必要です。このカードが表すのは、そうした強さ。ぐずぐずしたり、臆病風に吹かれている余裕はありません。迷いなく、スピーディーに行動を起こすこと。そして、ただひたすらに前進することを示しています。
big08	愛と辛抱強さによって、相手の心を動かす力	「力」というと、パワフルな男性を思い浮かべますが、このカードに描かれているのは、華奢な女性が、獰猛なライオンの顎に手を添えている姿。つまり、このカードが意味する「力」とは、腕力や権力ではありません。女性の頭上に描かれたインフィニティマークは、無限の愛の表れ、愛によって心を通わせられれば、相手を動かすことができる、というカードです。「手懐ける」ともいえるかもしれませんが、この力があれば、どんな人も味方に変えることができるのです。
big09	静かに自分の内面を探求する旅に出る	灰色の外套に身を包み、ひとり佇む老人。顔を横に向けた構図からもわかる通り、どこか人目を避けるような雰囲気があります。俗世間から離れて孤独に内面世界と向き合おうとしているのでしょう。例えば過去の自分の成功と失敗を振り返ったり、得た知識や経験を元に世の中の真理について思いを巡らせたり。手にしたランタンの中には真理へと導く六芒星の光があり、大切なのは見た目や外見ではなく、人間の本質であるということを示すカードです。
big10	逃れることのできない運命の悪戯	ゆっくりと回転する大きな車輪は運命を表すもの。その周りには時間を断ち切るソードを持つスフィンクス、生死を司るアヌビス、そして蛇が描かれています。また四隅にはエレメントの火・地・風・水に対応する聖獣が。すべての生命は逃れられない流れにあります。その過程では上昇するときも、下降するときもあるでしょう。このカードが出たら、自分の力ではどうしようもない運命により、思いがけない出来事が起こることを暗示しています。
big11	あくまでも公正中立にものごとを判断する	凛とした中世的な女性が中央に座っています。彼女は裁判官であり、左手の天秤で罪の重さを測り、右手の剣で裁きを下します。このカードは感情に振り回されずに、原因と結果を客観的に見つめて、公正な判断を下す冷静さを暗示します。このカードを引いた人が疚しい行いをしていたなら、その剣で容赦なく断罪されるでしょう。反対に誰かに不当な扱いを受けていたなら、アンバランスな状況を正す、救いの剣ともなるのです。
big12	手も足も出ないからこそ冴えてくる思考	後ろ手に縛られ、T字の木に片足で逆さ吊りにされた男性。その苦しい姿勢に反するように、表情はとても穏やかで、頭には後光が差しています。身動きが取れない静止した状態にあるからこそ、思考が冴え渡り、ますますクリアになっているのかもしれません。普段は慌ただしさの中で紛れてしまっていますが、こういう状態のほうが、本当の心の動きが鮮明にわかるものです。内省の時間を取ることの大切さを示したカードとも言えます。
big13	人生に終わりと始まりをもたらす冥界の使者	白馬に乗った死神が、静かに戦場を進んでいます。恐ろしい光景に見えるかもしれませんが、死はすべての生物の命のさだめ、決して忌むべきものではありません。また遠くに太陽が昇りつつあることにも注目してください。夜を越えて朝が来るように、死を超えた先に新たな生、はじまりがあるのです。つまり「死」のカードは、肉体の死というよりも、精神や人との関係、環境などの一新を表します。その先には必ず、新しい未来が用意されています。
big14	異なるものを混ぜ合わせ、生まれる有意義な何か	「節制」は「混ぜ合わせる」「結びつける」という意味のラテン語に由来するカードです。天使がふたつのカップに入った水を器用に操る様は、コミュニケーションを暗示しているかのよう。異なるものを混ぜ合わせれば化学反応が生じ、新しい何かが生まれます。また、その混ざり具合を調整することもあるでしょう。まったく別の人間と接点を持てば、様々な感情を体験したり、新しい気づきがあったりします。そんな人間関係の重要さを説くカードです。
big15	心に巣食う悪魔が果てしない欲望へと誘う	裸の男女が「悪魔」に捕らえられていますが、これは「恋人」のアダムとイブが誘惑に負けて、知恵の実を食べた後の姿と言われています。でも、その鎖はゆるく、表情も穏やか。逃げようと思えば逃げられるようにも見えます。囚われているのは体ではなく、快楽に溺れ、堕落した心であるということを象徴しているのでしょう。このカードが示すのは、自らの心に巣食う「悪魔」。一度自制心をなくすと、どこまでも流され、這い上がる気力すら奪われる、ということです。
big16	一瞬にして現状を変える、天の雷がもたらす崩壊	激しい稲妻によって崩れ去る「塔」。落下する王冠は、築き上げてきた権力の失墜を表しています。旧約聖書の「バベルの塔」を思い浮かべる人も多いでしょう。「衝撃的な変化」「予期せぬアクシデント」を意味するカードのため、怖いイメージを抱いている人もいるかもしれません。でも人生に変化は付き物。その時はショックで呆然とするかもしれませんが、破壊なくして新たな誕生はありません。凝り固まった固定観念や常識を一新する貴重な機会と捉えて。
big17	どんな時代も変わらずに人々を導く清らかな光	古くから「星」は旅人にとって、目指すべき方向を指し示す道標でした。このカードにひときわ大きく描かれたのはシリウス。古代エジプトでは航海の際に目印として用いられたそうです。さらに7つの星が、うら若き乙女の裸体を照らし出しています。穢れを知らない乙女は、この先、何にでもなれる可能性の象徴。また見上げた空に輝く星は、これから花開く才能も暗示します。何が起きようとも瞬き続ける「星」は、理想や希望を意味するカードです。
big18	月明かりに照らし出される幻影と心の真実	愁いを帯びた表情の「月」が昇っています。太陽とは異なり、月明かりの下ではすべてが朧気。何が正しいのか、何が現実なのかわからない、あいまいな状況を表すカードです。また日々、満ち欠けする月は感情の不安定さも表しています。その月を見上げて吠える犬と狼も、不穏なものを感じ取っている様子。潜在意識を意味する水の中から這い上がるザリガニは、心に隠している不安が表面に浮上しつつあることを表しているようです。
big19	未来の成長と成功を約束する存在	一点の曇りもない空に光り輝く「太陽」、そして馬に乗った裸の子供。包み隠すものは一切存在せず、すべてが照らし出されている光景が描かれています。子供の表情からも、すべてを楽しみ、喜びに溢れていることがわかるでしょう。いうまでもなく、太陽は万物の生命力の源で、生きる喜びとエネルギーを表しています。そして子供はこれからグングン成長していく存在。このカードを引いた時は、未来に大きな成功を収める可能性が暗示されているのです。
big20	終わったと思ったものが色鮮やかに蘇る	中央に描かれるのはラッパを吹く天使。そして棺からは死者が次々と蘇りつつあります。聖書の「最後の審判」をモチーフとしたカードであることは明らかです。ラッパの旗に描かれた十字は、人生の分岐点を表しているかのよう。人々に、何らかの決断を促しているのでしょう。死者が蘇ることから、「過去」との縁も深いカードです。過去に姿を消したものを、もう一度蘇らせるチャンスであることを知らせています。
big21	辿り着いた先に見える最高の景色	緑のリースの中央に描かれているのは、裸で舞うダンサー。両性具有の存在で、リースは「世界」の始まりとされる「宇宙卵」を表します。このカードが示すのはすべてが満ち足りた状態。手にした日本のワンドは、相反するものを自らのうちに結合し、完全な世界を作り上げたことを意味します。そして四隅にいるのは4つのエレメントに対応する聖獣で、世界が完成したことを表しています。まさに「長い時間をかけた末の完成」を象徴するカードです。
wands01	情熱を傾ける対象が見つかる	生命力の象徴「ワンド」を神の手が握りしめるA。背後の、城のような建物は達成したい目標の象徴とも取れます。念願のものを得たい、という強い意欲を表すカードです。
wands02	さらに高いステップを目指す	城の頂上に佇む、小さな地球を手にした成功者の姿です。既に栄光を手にしたものの、その結果に満足せず、新たな野心を抱きます。さらなる躍進への意欲を示すカードです。
wands03	動き出すチャンスを待つ	崖の上の男性が海を眺めています。船の旅立ちを見守っているのか、はたまた帰りを待っているのか。このカードは自分の現状や、次に目指す場所を思案することを表します。
wands04	自由を得て喜びに浸る	ワンドの向こうには、花束を振る人々の姿が。誰もがくつろぎ、穏やかに過ごしています。そして人々の背後に見える城は豊かさの象徴。安定した幸せと喜びを伝えるカードです。
wands05	強い意志で競い合う	青空の下、5人が意気揚々と戦いを繰り広げています。カードの明るい配色は、この戦いに悪い感情が伴わないことを示します。闘志と勝負へのこだわりを表すカードです。
wands06	大勢から称賛を浴びる	勝利のワンドを握りしめ、馬で練り歩く男性が描かれています。軍勢を率いていることからも、彼はきっと優秀な将なのでしょう。栄光を手にし、幸福感を得ることを表します。
wands07	主導権を握ったまま、勝負に挑む	孤軍奮闘する男性の様子が描かれています。突き上げるワンドの数から、敵の人数は多いようですが、彼の位置取りは有利な崖の上。圧倒的な優位は揺らがないことを示すカードです。
wands08	風のような速度で進展	8本のワンドが矢のように突き進む様子は、強いパワーとスピードを象徴します。また、同じ方向に揃ったワンドの先端は、意志と行動が一致し、迷いなく進む姿勢を暗示します。
wands09	自分を見つめ、あらゆる事態に備える	注意深く警戒する男性の姿が描かれています。傷を負いながらも、まだ戦おうとする姿は、負けたくないという不屈の精神を感じさせます。今一度、戦う理由を問いかけるカードです。
wands10	背負う重荷をどうするか決めるとき	いくつものワンドを抱える男性の姿です。その姿は「絶対に手放さない」という執着すら感じさせます。何を抱え、何を手放すべきなのか、カードが選択を投げかけています。
wands11	未来への希望を抱く	メッセンジャーの役割を担う少年です。自分よりも大きなワンドを見上げる姿は、彼の素直さを感じさせます。未来に希望を抱く純粋さと、それを実現しようとする熱意を表します。
wands12	新たな挑戦の意欲が沸き上がる	跳躍する馬に乗る勇敢な青年の姿が描かれています。活発で魅力的ですが、彼をコントロールするのは至難の業。制御不可能なほどの情熱と強い衝動を表すカードです。
wands13	花の香りのように漂う魅力	ワンドとヒマワリを手に持つ女性の姿です。挑発的に開いた足は、性的な魅力を感じさせ、足元の黒猫は魔性を表しているかのよう。裏表のない芯の強さを表現するカードです。
wands14	周囲を圧倒するカリスマ	いつでも立ち上がれるようにワンドに手を添える王が描かれています。その姿勢は行動力やカリスマ性を表し、足元を這うサラマンダーは情熱とパワーの象徴です。
pentacles01	確かな力で成功をつかむ	Aの神の手に乗るのは「豊かさ」を象徴するペンタクル。その下に広がる美しい庭の奥には険しい山々。努力によって、恵まれた環境に辿り着くことを表すカードです。
pentacles02	どんな変化にも適応する	曲芸師が踊りながら器用にふたつのペンタクルを操っている様子が描かれています。どんな状況になろうとも、上手にものごとを扱う、適応力や柔軟性を表しています。
pentacles03	今までの努力が形になる	台の上に乗るのはひとりの彫刻家。その腕が認められ、大きな仕事を任されようとしています。これまでの努力やスキルが評価されるなど、チャンスの到来を教えるカードです。
pentacles04	豊かな生活への執着	ペンタクルを大切に抱えた守銭奴。背後に描かれる町並みは、彼が世の中のお金の流れに関わっていることを意味しているよう。強い所有欲と豊かさへの執着を表すカードです。
pentacles05	状況の悪化から訪れる試練	教会の前をふたりの人物が通り過ぎようとしています。プライドがあるため、教会には物乞いをしないつもりです。生きることの厳しさを表しているカードと言えるでしょう。
pentacles06	与える者と与えられる者	片手に天秤を持つ権力者が、人々に何かを与えている様子が描かれています。権力者は善意で心を満たし、人々は善意を受け取るという、相互関係を表すカードです。
pentacles07	状況を見極める必要がある	収穫したペンタクルの果実を、憂鬱そうな表情で見つめる男性。どうやら苦労した割には、成果に納得できていないのでしょう。このカードは成長のために乗り越える壁を暗示します。
pentacles08	忍耐と努力で得られるもの	黙々とペンタクルを作っている真面目そうな職人。背後の町は、彼の仕事が人々に認められる日が来ることを意味しているよう。努力の積み重ねを示すカードです。
pentacles09	高い地位に昇格する	知性の象徴である隼を手なずけた優雅な女性。後方の城のような建物は、彼女が高い身分にあることを証明しているよう。魅力と才能により、成功を勝ち取ることを意味します。
pentacles10	過去から未来に受け継ぐもの	裕福な暮らしをする家庭が描かれています。中央の男性の手には、魔術のワンド。左にいる老人から力を受け継いだのでしょうか。継承や繁栄を表すカードと言えます。
pentacles11	学びが未来を明るくする	両手で掲げたペンタクルをじっと見つめる少年。とても研究熱心で勤勉な様子が見て取れます。焦ることなく、様々なスキルをものにすることで、将来的に大きく開花する可能性が。
pentacles12	勤勉さで運命を切り開く	黒馬に跨り、鎧を身にまとった「ペンタクルのナイト」。遠くを見つめている様子は、成功した未来を思い描いているのでしょう。勤勉な取り組みを表すカードと言えます。
pentacles13	母のような優しさで幸せをつかむ	穏やかな表情でペンタクルを見つめるクイーン。足元のウサギは豊穣の象徴。母のようにすべてを受け入れる慈愛の心が、富や幸せを運ぶと教えてくれるカードです。
pentacles14	己の実力によって人を導く	落ち着いた風貌でペンタクルを手にし、玉座についているキング。周囲の植物は手にした富の現れです。王として、持てるものを人々のために使うことをテーマにしています。
swords01	自分の力で未来を勝ち取る	ソードを握る神の手。剣先には勝利を表す王冠が。背景の険しい山々を見る限り、決して楽な道程ではなく、強い精神力によって過酷な状況を打開することを表すカードです。
swords02	決めきれずに迷っている	両手にソードを持つ女性。目隠しをしているのは、見たくないものがあるのか、自分の本心を隠したいと思っているのでしょうか。2本のソードは板挟み状態の暗示かもしれません。
swords03	悲しみで傷つくハート	3本のソードが突き刺さっているのは、愛や心臓を象徴するハート。悲しみを表す雨に打たれています。心が深く傷つくようなショッキングなできごとを暗示するカードです。
swords04	今後に備えた休息	騎士の彫像が、目を閉じ、横たわっています。胸の上で手を合わせているのは何かを祈っているからでしょうか。静かな休息やひとりの時間を表しているカードです。
swords05	力づくで得る勝利	戦いに敗れたふたりを見つめながら、ソードを突き立てる男。ここで何らかの争いが起きたようです。ニヤリと笑う男の姿と、不穏な雲の形が「虚しい勝利」を表します。
swords06	悪い現状から抜け出す	船を漕ぐ男性。前に座っているふたりの親子。俯いて顔を隠しているのは、すべてを捨てて逃げていくからでしょうか。流れに身を任せ、新天地へ向かうことを表します。
swords07	隠れて悪だくみをする	後ろを振り返りながら、ソードを持ち逃げしようとする男性が描かれています。遠くに人影がありますが、まだ気づかれていない様子。「不正」や「不義」を暗示するカードです。
swords08	束縛されて自由を失う	目隠しをされ、縛られた女性がソードに囲まれています。しかし、よく見ると周りに見張りはおらず、足は自由。囚われているのは体ではなく、心であることを示しています。
swords09	目を覆いたくなる悲しみ	ベッドの上で嘆く女性。悪夢を見ていたのか、絶望から夜通し泣いていたのか・・・。悲しみや不安、罪の意識を表すカードです。ただ、同時に、そこからの目覚めも暗示しています。
swords10	始まりのための終わり	10本のソードで刺された男性。一見、不吉な絵柄ですが、よく見ると暗い空が晴れ渡ろうとしています。現実を受け入れることで、明るい未来を手に入れられることを表します。
swords11	現状を冷静に分析する	ソードを持ち、鋭い視線で背後を振り返る青年。頭上を飛ぶ鳥の群れは、彼の精神の高ぶりを表します。一触即発の状況にも対応する用心深さや、分析力を示すカードです。
swords12	迷いなく目的へと突進	白馬に跨り駆ける騎士。視線はまっすぐ前方に向けられています。掲げたソードは彼の意志と自信の現れ、迷いなく突き進む心の強さや、勇敢さを象徴する一枚です。
swords13	現実を見据えた正しい行動	玉座に座り、目の前を見据えるクイーン。右手に持つソードは天空を向き、左手はすべての過ちをも受け入れようとしています。厳しさと優しさの両面を持つカードと言えるでしょう。
swords14	冷静な判断で答えを出す	険しい表情で正面を向いているキング。これまでの歴戦の経験から、自分の正しさを確信しています。手にしたソードは、甘えや、なあなあの態度を許さない、厳しさを表します。
cups01	愛のすばらしさを謳歌する	Aの神の手に乗る聖杯から落ちる水は、慈愛に富んだ心を象徴しています。聖杯に飛び込もうとする白い鳩は平和と幸福の使い。精神的な充実を表す一枚です。
cups02	愛と絆が芽生える時	リースを乗せた男女が、互いにカップを差し出しています。叡智と癒しを表す翼を持つ獅子と、ヘルメスの杖は、まるで相合傘のようにも。人との絆や信頼を象徴するカードです。
cups03	喜びを分かち合いたい衝動	軽やかにステップを踏みながら、3人の乙女が豊穣を祝います。これまでの苦労や努力が実を結んだのでしょう。他人と喜びを共有することのすばらしさを表すカードです。
cups04	退屈から抜け出すための一歩	木にもたれ、退屈そうに座る男性です。目の前に差し出されたカップにすら気づいていない様子からは、思考を停止し、目の前の現状を見つめようとしない姿勢が見て取れます。
cups05	失った先で新たなものを得る	倒れたカップを見て、嘆き悲しむ男性。とはいえ、その後ろにはまだふたつのカップがあり、希望の灯火は消えていません。ただし、見方を変える必要がある、と告げています。
cups06	過去の美しい思い出を振り返る	花いっぱいのカップを前に喜ぶ子供は、無邪気だった頃の自分の象徴でしょう。過ぎ去った時間への懐かしさと、感傷を呼び起こします。温かな過去を象徴するカードです。
cups07	夢に惑い、本来の望みを見失う	知恵の蛇、栄誉の花冠など、目の前に魅力的な宝物が並びます。しかしカップは雲の上。まだ現実的には何も手に入れておらず、ただ甘い妄想に浸っていることを表します。
cups08	区切りを迎え、再び歩み始める	積まれたカップを背に、男性がその場を離れようとしています。険しい山々に向かう姿は、現状に区切りをつけ、また新たな目標に向けて歩み出すことを暗示しています。
cups09	栄光を得て、一息つく	9つのカップを背に、腕を組む男性が描かれています。カップが示すのは、精神的・物質的な喜び、自分の得たものを誇らしく感じつつ、それを味わう段階を表します。
cups10	愛と希望に満たされる日々	愛と想像力を象徴するカップが、空の上で虹を形作ります。虹の下には、喜びに満ちた家族の姿。これまでの努力が実を結び、幸福で愛に溢れた時間がやってくる暗示です。
cups11	穏やかに受け入れ、自分の力に変える	背景の海は、想像力と霊性の象徴です。それはまるでカップの魚に向かって微笑む少年の、感受性の豊かさを表すかのよう。すべてを受け入れる心を表現したカードです。
cups12	憧れに向かって走り出す	物語の中の白馬の騎士のように、凛々しく美しい青年の姿です。翼を模した兜と靴は、憧れに対する願望の現れ、理想と夢に向かって、険しい山に向け、歩き出そうとしています。
cups13	他人に寄り添う、深い慈愛	玉座に腰掛け、豪華なカップを見つめる女王が描かれています。海や大地と一体化するドレスの裾は、すべてを受け入れる彼女の優しさの現れ。無条件の愛を象徴するカードです。
cups14	人の心も、人生の波も受け入れる	海の上の玉座に、穏やかな表情で腰掛ける王の姿です。波の動きさえ楽しんでいる様子、背景に描かれたイルカの姿は、彼の遊び心や寛大さを感じさせるようです。
   
";


        $ex_str = explode("\n", $str);
        foreach ($ex_str as $v) {
            if (trim($v) == "") {
                continue;
            }

            list($key, $prof1, $prof2) = explode("\t", trim($v));

            $update = [];
            $update['prof1'] = trim($prof1);
            $update['prof2'] = trim($prof2);

            echo trim($key);
            echo "\n";
            print_r($update);
            echo "\n";
            echo "\n";
            echo "\n";

            DB::table("t_tarot")->where("image", "=", trim($key))->update($update);

        }


    }
}
