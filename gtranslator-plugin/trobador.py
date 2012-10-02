from gi.repository import GObject, Gtk, Gtranslator, PeasGtk
from panel import Panel
import sys
from gettext import _


class TrobadorPlugin(GObject.Object, Gtranslator.TabActivatable, PeasGtk.Configurable):
    __gtype_name__ = "TrobadorPlugin"

    tab = GObject.property(type=Gtranslator.Tab)
    handler_id = None
    project = ''
    version = ''
    host = ''
    default_host = 'trobador.trasno.net'
    check = Gtk.CheckButton()
    project_entry = Gtk.Entry()
    version_entry = Gtk.Entry()
    host_entry = Gtk.Entry()
    save_button = Gtk.Button(label="Save")
    save_host_button = Gtk.Button(label="Save")

    def __init__(self):
        GObject.Object.__init__(self)

    def do_activate(self):

        self.window = self.tab.get_toplevel()
        self.create_panel()
        TrobadorPlugin.host = self.default_host
        self.tab.add_widget(self.panel, "GtrTrobador", _("Trobador"), "results panel", Gtranslator.TabPlacement.RIGHT)

    def do_deactivate(self):
        print "Removing..."
        self.tab.remove_widget(self.panel)
        self.tab.disconnect(self.handler_id)

    def do_update_state(self):
        pass

    def check_checkButton_state(self, check):

        if self.check.get_active():
            print "activate"
            self.project_entry.set_editable(True)
            self.version_entry.set_editable(True)
            self.save_button.set_sensitive(True)
        else:
            print "deactivate"
            self.project_entry.set_text("")
            self.version_entry.set_text("")
            self.project_entry.set_editable(False)
            self.version_entry.set_editable(False)
            self.save_button.set_sensitive(False)
            TrobadorPlugin.project = ''
            TrobadorPlugin.version = ''

    def do_create_configure_widget(self):

        table = Gtk.Table(8, 2, True)

        if not self.check.get_active():
            self.project_entry.set_editable(False)
            self.version_entry.set_editable(False)
            self.save_button.set_sensitive(False)

        #self.check = Gtk.CheckButton("Seleccionar proyecto y version")
        self.check.set_label("Select project & version")
        self.check.set_border_width(6)
        self.check.connect("clicked", self.check_checkButton_state)

        project_label = Gtk.Label("Project")
        #self.proyectoEntry = Gtk.Entry()
        self.project_entry.set_text(TrobadorPlugin.project)

        version_label = Gtk.Label("Version")
        #self.version_entry = Gtk.Entry()
        self.version_entry.set_text(TrobadorPlugin.version)

        #save_button = Gtk.Button(label="Guardar")
        self.save_button.set_label("Save")
        self.save_host_button.set_label("Save")

        hostLabel = Gtk.Label("Host")

        if self.host == '':
            self.host_entry.set_text(TrobadorPlugin.default_host)
        else:
            self.host_entry.set_text(TrobadorPlugin.host)

        info_label1 = Gtk.Label("Project settings")
        info_label2 = Gtk.Label("Host settings")

        table.attach(info_label1, 0, 2, 0, 1)

        table.attach(self.check, 0, 2, 1, 2)
        table.attach(project_label, 0, 1, 2, 3)
        table.attach(self.project_entry, 1, 2, 2, 3)
        table.attach(version_label, 0, 1, 3, 4)
        table.attach(self.version_entry, 1, 2, 3, 4)
        table.attach(self.save_button, 0, 1, 4, 5)

        table.attach(info_label2, 0, 2, 5, 6)

        table.attach(hostLabel, 0, 1, 6, 7)
        table.attach(self.host_entry, 1, 2, 6, 7)
        table.attach(self.save_host_button, 0, 1, 7, 8)

        self.save_button.connect("clicked", self.save_config, self.project_entry.get_text(), self.version_entry.get_text())

        self.save_host_button.connect("clicked", self.save_host_config, self.host_entry.get_text())

        return table

    def save_config(self, save_button, project, version):
        TrobadorPlugin.project = self.project_entry.get_text()
        TrobadorPlugin.version = self.version_entry.get_text()

    def save_host_config(self, save_host_button, host):

        if self.host_entry.get_text() != '':
            TrobadorPlugin.host = self.host_entry.get_text()
        else:
            TrobadorPlugin.host = self.default_host
            self.host_entry.set_text(TrobadorPlugin.host)

    def create_panel(self):
        self.panel = Panel()
        self.panel.set_host(TrobadorPlugin.default_host)
        tree = self.panel.get_tree()
        tree.connect("row-activated", self.set_buffer)

        self.get_translation_unit

        self.handler_id = self.tab.connect("showed-message", self.get_translation_unit)

        self.panel.show()

    def set_buffer(self, tree, row, col):
        iterator = self.panel.get_iterator()
        # l = tree.get_model()
        #rootiter = l.get_iter_first()

        selection, iterator = tree.get_selection().get_selected()

        if iterator != None:
            view = self.window.get_active_view()
            if not view or not view.get_editable():
                return "no editable"

            document = view.get_buffer()
            document.begin_user_action()
            iters = document.get_selection_bounds()
            if iters:
                document.delete_interactive(iters[0], iters[1], view.get_editable())

            document.insert_interactive_at_cursor(selection.get_value(iterator, 0), -1, view.get_editable())
            document.end_user_action()

    def get_translation_unit(self, tab, msg):
        po_file = GObject.property(type=Gtranslator.Po)
        po_file = self.tab.get_po()
        print msg.get_msgid()
        msg = po_file.get_current_message()
        c = msg[0].get_msgid()
        self.panel.set_translation_unit(c)
        self.panel.set_project(self.project)
        self.panel.set_version(self.version)
        self.panel.set_host(self.host)
        print "hola: " + self.panel.get_host()
        # Updating the results
        self.panel.update_data()

# ex:et:ts=4:
