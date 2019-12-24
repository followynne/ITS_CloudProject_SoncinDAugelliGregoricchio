import * as func from './galleryGetBlobsFunc.js';

$(document).ready(() =>
{

  fetchSelectedPage(1);
  getSearchedImagesByMultipleData('#btnsearchall');
  getSearchedImages('#btnsearchfortags');
  getSearchedImages('#btnsearchforbrands');
  getSearchedImages('#btnsearchfordates');
  confirmPageOpen('#fetchall');
  selectOrDeselectAllImagesInPage('#selectall',true);
  selectOrDeselectAllImagesInPage('#deselectall',false);
  deleteSelectedImages('#deleteselected');
  getShareableLink('#getlink');

})

function fetchSelectedPage(index)
{
    fetch('/getblobs?indexpage='+(index-1)).then((result) => (result.json())).then((data) => {
    func.displayImagesForSubPage(data.pageData.blobs, data.pageData.tempToken);
    if (!(window.location.pathname === '/completegallery.php')){
      startPaginationCreation(data.pageData, index-1);
    }
    enableDeleteOneIfNoneAreSelected();
    enableDeleteForSingleImage(data.pageData, index-1);
  }).catch((err) => {
      console.log('Potresti non aver foto al momento o aver eliminato l\'ultima pagina di foto.');
      alert('If you have photos, refresh page please. Check log if info needed.')})
};

function startPaginationCreation(dataJson, indexPage)
{
  let pagesToRender = Math.ceil(dataJson.totalBlobsCount/dataJson.maxBlobsPerSubPage);
  func.createPagination(pagesToRender, indexPage);

  $('.fetchSelectedPage').on('click', (event) => {
    fetchSelectedPage($(event.target).attr('value'));
  })
  $('.fetchPagination').on('click', (event) => {
    startPaginationCreation(dataJson, $(event.target).attr('value')-1);
  })
}

function getSearchedImages(id, indexPage)
{
  $(id).on('click', (event) => {
    let body = new FormData();
    let inputId = $(id).siblings('input').attr('id');
    let elName = $(id).siblings('input').attr('name');
    let dataInserted = getWordsSplittedBySpace('#'+inputId);
    if (dataInserted[0] == ""){
      $('#sharelink').val('');
      fetchSelectedPage(1);
      return;
    }
    let data = JSON.stringify(dataInserted);
    body.append(elName, data);
    body.append('indexpage', indexPage = 0);
    var myInit = { method: 'POST',
                body: body,
                };
    fetchSearchedPage(indexPage = 1, myInit, id);
  })
}

function getSearchedImagesByMultipleData(id, indexPage){
  $(id).on('click', (event) => {
    let body = new FormData();
    let arrayInput = $.map($('.inputdata'), (value, index) => {
      return [value];
    })
    let arrToAppend = {};
    $.each(arrayInput, (index, value) => {
      let inputId = $(value).attr('id');
      let elName = $(value).attr('name');
      let dataInserted = getWordsSplittedBySpace('#'+inputId);
      if (dataInserted[0] == ""){
        return;
      }
      arrToAppend[elName] = dataInserted;
    })
    let data2 = JSON.stringify(arrToAppend);
    body.append('data', data2);
    body.append('indexpage', indexPage = 0);
    var myInit = { method: 'POST',
                body: body,
                };
    fetchSearchedPage(indexPage = 1, myInit, id);
  })
}

function getWordsSplittedBySpace(id)
{
  let tags = $(id).val().trim();
  let tags_splitted = tags.split(" ");
  let tagsfinal = [];
  $.each(tags_splitted, (index, value) => {
    let val = '';
    if (id == '#datesinput'){
      val = value.replace(/-/g, '');
    } else {
      val = value.replace(/-/g, ' ');
    }
    tagsfinal.push(val.trim());
  })
  return tagsfinal;
}

function fetchSearchedPage(index, postPar)
{
  fetch('/search', postPar).then((result) => (result.json())).then((data) => {
    func.displayImagesForSubPage(data.pageData.blobs, data.pageData.tempToken);
    if (!(window.location.pathname === '/completegallery')){
      startPaginationCreationForSearch(data.pageData, index-1, postPar);
    }
    enableDeleteOneIfNoneAreSelected();
    enableDeleteForSingleImage(data.pageData, index-1);
  }).catch((err) => {
    console.log(' Nessun risultato per la ricerca effettuata su: ');
    for(var pair of postPar.body.entries()){
      console.log(pair[0] + ': ' + pair[1]);
    }
    alert(' Nessun risultato per la ricerca effettuata. Verrà ricaricata la prima pagina delle immagini.');
    fetchSelectedPage(1);
  })
};

function startPaginationCreationForSearch(dataJson, indexPage, postPar)
{
  let pagesToRender = Math.ceil(dataJson.totalBlobsCount/dataJson.maxBlobsPerSubPage);
  func.createPagination(pagesToRender, indexPage);

  $('.fetchSelectedPage').on('click', (event) => {
    postPar.body.set('indexpage', $(event.target).attr('value')-1);
    fetchSearchedPage($(event.target).attr('value'), postPar);
  })
  $('.fetchPagination').on('click', (event) => {
    startPaginationCreationForSearch(dataJson, $(event.target).attr('value')-1);
  })
}

function confirmPageOpen(id)
{
  $(id).on('click', (event)=>{
    if (!confirm("L'apertura di questa pagina mostra tutte le immagini che hai salvato in cloud."+
    "A seconda del numero presente, può portare a una pagina molto pesante o richiedere un alto consumo di banda."+
    "Sei sicuro di volerla aprirla?")) {
      event.preventDefault();
    }
  })
}

function enableDeleteOneIfNoneAreSelected()
{
  $('.blankCheckbox').on('click', (event) => {
    $('.divForImagesShowing input:checked').length>0 ? $('.btnDeleteOne').prop('disabled', true) : $('.btnDeleteOne').removeAttr('disabled');
  })
}

function enableDeleteForSingleImage(dataJson, indexPage)
{
  $('.btnDeleteOne').on('click', (event) => {
    var myInit = { method: 'DELETE'};
    fetch('/deleteblobs?name='+$(event.target).val(), myInit).then((result) => (result.text())).then((data) => {
      if (data!= 'successful delete'){
        alert("Delete not successful.");
        return;
      }
      fetchSelectedPage(indexPage+1);
    })
  })
}

function selectOrDeselectAllImagesInPage(id, boolValue)
{
  $(id).on('click', (event) => {
    $('.blankCheckbox').prop('checked', boolValue);
    id == '#selectall' ? $('.btnDeleteOne').prop('disabled', true) : $('.btnDeleteOne').removeAttr('disabled');
  })
}

function deleteSelectedImages(id)
{
  $(id).on('click', (event) => {
    let parameterImages = func.createQueryStringFromImagesValue('.divForImagesShowing input:checked');
    if (parameterImages === ''){
      return;
    }
    var myInit = { method: 'DELETE'};
    fetch('/deleteblobs?'+parameterImages, myInit).then((result) => (result.text())).then((data) => {
      if (data!= 'successful delete'){
        alert("Some deletes were not successful. Please check after page reload.");
      }
      fetchSelectedPage($('.pagination li.active').children('a').attr('value'));
    })
  })
}

function getShareableLink(id)
{
  prepareShareUsage();
  $(id).on('click', (event) => {
    let body = new FormData();
    let expirydate = validateDate();
    let parameterImages = func.getValueOfSelectedImages('.divForImagesShowing input:checked');

    if (parameterImages == '' || expirydate == false ){
      return;
    }

    $('[data-toggle="popover"]').popover('hide');
    let imgs = JSON.stringify(parameterImages);
    body.append('expirydate', expirydate);
    body.append('imgname', imgs);
    var myInit = { method: 'POST',
                body: body,
                };
    fetch('/shareablelink', myInit).then((result) => (result.text())).then((data) => {
      $('#sharelink').val(data);
    }).catch((err) => {console.log('Sono sorti problemi con la creazione del link.');})
  })
}

function prepareShareUsage()
{
  let d = new Date();
  let d2 = new Date();
  d.setHours(d.getHours()+2);
  d2.setMonth(d.getMonth()+6);
  let x = $('#datetimepicker').attr('min', d.getFullYear()  + '-' + ('0' + (d.getMonth()+1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2));
  let x2 = $('#datetimepicker').attr('max', d2.getFullYear() + '-' + ('0' + (d2.getMonth()+1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2));
  $('[data-toggle="popover"]').popover()
  let clipboard = new ClipboardJS('.copy');
}

function validateDate()
{
  let date = $('#datetimepicker').val();
  let hour = $('#hourpicker').val();
  let actualDate = new Date();
  actualDate.setHours(actualDate.getHours()+2);
  let choosenDate = new Date(date + 'T' + hour +'Z');
  if (date == '' || hour == '' || actualDate>choosenDate){
    return false;
  }
  choosenDate.setHours(choosenDate.getHours()-2);
  return choosenDate.getTime();
}
