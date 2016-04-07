# DocuDipity

## Digital and Semantic Publishing Lab (DASPLab)

### DISI - University of Bologna

[DocuDipity](http://eelst.cs.unibo.it/docudipity/) is an interactive web-based tool aimed at supporting the exploration and analysis of heterogeneous document collections. Some documents are preloaded and a drop-down list at the top of the page let the user select the document to focus on. Moreover, two buttons allows to browse the document list quickly and to open the related XML source in a new tab, respectively. 

It is worth noting that DocuDipity is a general tool that can work on any XML document, without requiring any background information about the format documents are written in. In fact, DocuDipity engine is based on the structural pattern theory [1]. By leveraging the pattern identification algorithm described in [2], DocuDipity is able to extract relevant information about the structure of the set of documents provided as input, and use it (a) to visualise the documents and (b) to provide an analysis framework to inspect their content easily.

The DocuDipity interface is composed by three parts, as shown in ![FIGURE](https://github.com/fpoggi/DocuDipity/blob/master/documentation/images/DocuDipity1.png). On the right side, the content of the
document is displayed as an hypertext; on the left side, a zoomable view based on the SunBurst technique [] pro-
vides an overview of the whole document structure; in the bottom, the user can write JavaScript and CSS codes to de-
fine customized patterns and interactively evaluating them on-the-fly.

### Bibliography

[1] Poggi, F. (2015). Structural patterns for document engineering: from an empirical bottom-up analysis to an ontological theory. Ph.D. Thesis. Department of Computer Science and Engineering, University of Bologna, Italy. http://amsdottorato.unibo.it/7123/4/Poggi_Francesco_Tesi.pdf

[2] Di Iorio, A., Peroni, S., Poggi, F., Vitali, F. (2012). A first approach to the automatic recognition of structural patterns in XML documents. In Proceedings of the 2012 ACM symposium on Document Engineering (DocEng 2012): 85–94. New York, New York, USA: ACM. http://dx.doi.org/10.1145/2361354.2361374

[3] Stasko, J., Zhang, E. (2000). Focus+ context display and navigation techniques for enhancing radial, space-filling hierarchy visualizations. In the 2000 IEEE Symposium on Information Visualization (InfoVis 2000): 57–65. Washington, District Columbia, USA: IEEE.



