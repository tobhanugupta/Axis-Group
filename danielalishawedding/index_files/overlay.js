google.maps.__gjsload__('overlay', '\'use strict\';function sQ(a){this.j=a}R(sQ,V);Wa(sQ[L],function(a){"outProjection"!=a&&(a=!!(this.get("offset")&&this.get("projectionTopLeft")&&this.get("projection")&&pe(this.get("zoom"))),a==!this.get("outProjection")&&this.set("outProjection",a?this.j:null))});function tQ(){}function uQ(){var a=this.gm_props_;if(this[bn]()){if(this[Hc]()){if(!a.Qg&&this.onAdd)this.onAdd();a.Qg=!0;this.draw()}}else{if(a.Qg)if(this[Sc])this[Sc]();else this[Cb]();a.Qg=!1}}function vQ(a){a.gm_props_=a.gm_props_||new tQ;return a.gm_props_}function wQ(a){Vi[N](this);this.la=T(a,uQ)}R(wQ,Vi);function xQ(){}\nxQ[L].j=function(a){var b=a[Zm](),c=vQ(a),d=c.Lc;c.Lc=b;d&&(c=vQ(a),(d=c.Fa)&&d[Dm](),(d=c.gj)&&d[Dm](),a[Dm](),a.set("panes",null),a.set("projection",null),S(c.$,U[vb]),c.$=null,c.ff&&(c.ff.la(),c.ff=null),mr("Ox","-p",a));if(b){c=vQ(a);d=c.ff;d||(d=c.ff=new wQ(a));S(c.$,U[vb]);var e=c.Fa=c.Fa||new uq,f=b[C];e[r]("zoom",f);e[r]("offset",f);e[r]("center",f,"projectionCenterQ");e[r]("projection",b);e[r]("projectionTopLeft",f);e=c.gj=c.gj||new sQ(e);e[r]("zoom",f);e[r]("offset",f);e[r]("projection",b);\ne[r]("projectionTopLeft",f);a[r]("projection",e,"outProjection");a[r]("panes",f);e=T(d,d.Y);c.$=[U[A](a,"panes_changed",e),U[A](f,"zoom_changed",e),U[A](f,"offset_changed",e),U[A](b,"projection_changed",e),U[A](f,"projectioncenterq_changed",e),U[v](b,"forceredraw",d)];d.Y();b instanceof Ig&&(jr(b,"Ox"),lr("Ox","-p",a))}};var yQ=new xQ;th.overlay=function(a){eval(a)};Vf("overlay",yQ);\n')