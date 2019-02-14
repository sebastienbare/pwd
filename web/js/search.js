document.getElementById('advanced-search').addEventListener('click',function(){
    if(document.getElementById('titre') != null)
    {
        document.getElementById('mydiv').innerHTML = "";
        var tab = ['genre', 'producteur', 'annee'];
        for(var cpt=0; cpt < 3; cpt++)
        {
            document.getElementById('mydiv').innerHTML += '<input type="text" name="'+tab[cpt]+'" placeholder="'+tab[cpt]+'" id="'+tab[cpt]+'" class="btn-group center" />';
        }
        document.getElementById('mydiv').innerHTML +='<button type="submit" class="btn-form"><span class="icon-magnifier search-icon"></span>RECHERCHER<i class="pe-7s-angle-right"></i></button>';
    }
});

document.getElementById('search').addEventListener('click',function(){
    if(document.getElementById('genre') != null)
    {
        document.getElementById('mydiv').innerHTML ='<input type="text" name="titre" placeholder="titre de la série" id="titre" class="btn-group1 center" /><button type="submit" class="btn-form"><span class="icon-magnifier search-icon"></span>RECHERCHER<i class="pe-7s-angle-right"></i></button>';
    }
});
