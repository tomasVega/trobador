from gi.repository import Gtk
import urllib
from xml.dom.minidom import parseString

class Panel(Gtk.Box):
    __gtype_name__ = "Panel"

    results = []
    translation_unit = ''
    project = ''
    version = ''
    host = 'zf.local'

    # Initialize results panel
    def __init__(self):
        Gtk.Box.__init__(self)

        data = self.get_data(self.translation_unit)
        if data != None:
            self.parse_set_data(data)

        self.paned = Gtk.Paned.new(Gtk.Orientation.VERTICAL)
        # Create the list of items
        self.liststore = Gtk.ListStore(str)
        for i in self.results:
            self.treeiter = self.liststore.append([i])

        # Adding the list
        self.treeview = Gtk.TreeView(self.liststore)

        # Column
        cell = Gtk.CellRendererText()
        col = Gtk.TreeViewColumn("Results", cell, text=0)

        col.set_sizing(2)
        #Gtk.TREE_VIEW_COLUMN_AUTOSIZE
        self.treeview.append_column(col)

        # Adding the list to the panel
        self.paned.pack2(self.treeview, True, True)
        self.paned.set_position(150)

        # Showing panel
        self.paned.show_all()
        self.pack_start(self.paned, True, True, 0)

    # Deleting the list of results
    def delete_list(self):
        self.liststore = Gtk.ListStore(str)
        self.treeiter = Gtk.TreeIter()
        self.treeview.set_model(self.liststore)

    # Deleting the tree
    def delete_data(self):
        self.liststore = Gtk.ListStore(str)
        self.treeiter = Gtk.TreeIter()
        col = self.treeview.get_column(0)
        self.treeview.remove_column(col)
        self.treeview = Gtk.TreeView(self.liststore)

    # When selecting a new string to translate the search is performed against WS results
    def update_data(self):
        data = self.get_data(self.translation_unit)
        if data != None:
            self.parse_set_data(data)

            new_liststore = Gtk.ListStore(str)
            for i in self.results:
                new_treeiter = new_liststore.append([i])

            self.liststore = new_liststore
            self.treeiter = new_treeiter
            # Adding the list
            self.treeview.set_model(self.liststore)
        # If there's no match the list is deleted
        else:
            self.delete_list()

    # Getting data from WS
    def get_data(self, translation_unit):
        url = 'http://' + self.get_host() + '/webservices/webservices/index?method=getResults&cadena=' + translation_unit + '&proyecto=' + self.get_project() + '&version=' + self.get_version()
        try:
            u = urllib.urlopen(url)
            data = u.read()

            dom = parseString(data)
            length = dom.getElementsByTagName('response').length
            # if no results matched
            if length == 1:
                return None

            return data

        except:
            print "Error, wrong mirror"
            return None

    # Parsing xml file
    def parse_set_data(self, data):
        #deleting previous results
        self.results = []

        dom = parseString(data)
        length = dom.getElementsByTagName('seg').length
        xml_tags = dom.getElementsByTagName('seg')

        i = 1
        for i in range(length):
            if (i % 2) == 1:
                tag = xml_tags[i].toxml()
                translated_unit = tag.replace('<seg>', '').replace('</seg>', '')
                self.results.append(translated_unit)

    # Getting the treeview
    def get_tree(self):
        return self.treeview

    # Getting the iterator
    def get_iterator(self):
        return self.treeiter

    # Setting the translation unit
    def set_translation_unit(self, translation_unit):
        self.translation_unit = translation_unit

    # Getting the translation unit
    def get_translation_unit(self):
        return self.translation_unit

    # Setting the project
    def set_project(self, project):
        self.project = project

    # Getting the project
    def get_project(self):
        return self.project

    # Setting the version
    def set_version(self, version):
        self.version = version

    # Getting the version
    def get_version(self):
        return self.version

    # Setting de host
    def set_host(self, host):
        self.host = host

    # Getting the host
    def get_host(self):
        return self.host

# ex:et:ts=4:
