function  renderPagination(links){
    links.forEach(function(each){
        $('#paginationId').append($('<li>').attr('class',`page-item  ${each.active ? 'active' : ''}`)
            .append(`<a class="page-link" href="${each.url}"> ${each.label} </a>`));
    })
    links.forEach(function(each){
        $('#paginationId2').append($('<li>').attr('class',`page-item  ${each.active ? 'active' : ''}`)
            .append(`<a class="page-link" href="${each.url}"> ${each.label} </a>`));
    })
}
